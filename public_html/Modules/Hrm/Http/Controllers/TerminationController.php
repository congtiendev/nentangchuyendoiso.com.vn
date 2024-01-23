<?php

namespace Modules\Hrm\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\Termination;
use Modules\Hrm\Entities\TerminationType;
use Modules\Hrm\Events\CreateTermination;
use Modules\Hrm\Events\DestroyTermination;
use Modules\Hrm\Events\UpdateTermination;
use App\Models\CustomNotification;
use App\Models\UserNotifications;
use App\Models\ActivityLogTermination;

class TerminationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('termination manage')) {
            if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $terminations = Termination::where('employee_id', '=', \Auth::user()->id)->where('workspace', getActiveWorkSpace())->get();
            } else {
                $terminations = Termination::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->with(['users', 'terminationType'])->get();
            }
            return view('hrm::termination.index', compact('terminations'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function activityLogTermination()
    {
        if (Auth::user()->isAbleTo('termination manage')) {

            $terminations = ActivityLogTermination::latest()->get();

            return view('hrm::termination.activityLog', compact('terminations'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('termination create')) {
            $employees   = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');

            $terminationtypes      = TerminationType::where('workspace', getActiveWorkSpace())->where('created_by', '=', creatorId())->get()->pluck('name', 'id');
            return view('hrm::termination.create', compact('employees', 'terminationtypes'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('termination create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'termination_type' => 'required',
                    'notice_date' => 'required|after:yesterday',
                    'description' => 'required',
                    'termination_date' => 'required|after_or_equal:notice_date',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $termination                   = new Termination();
            $employee = Employee::where('user_id', '=', $request->employee_id)->first();
            if (!empty($employee)) {
                $termination->employee_id = $employee->id;
            }

            $termination->user_id          = $request->employee_id;
            $termination->termination_type = $request->termination_type;
            $termination->notice_date      = $request->notice_date;
            $termination->termination_date = $request->termination_date;
            $termination->description      = $request->description;
            $termination->workspace        = getActiveWorkSpace();
            $termination->created_by       = creatorId();
            $termination->save();
            $action = 'Tạo mới miễn nhiệm';

            $typeAction = 'store';
            
            $nameType = TerminationType::find($request->termination_type)->name;
            $userName = User::find($termination->user_id)->name;
            
            $changes = [
                'user_id'          => $request->employee_id,
                'name'             =>$userName,
                'termination_type' => $nameType,
                'notice_date'      => $request->notice_date,
                'termination_date' => $request->termination_date,
                'description'      => $request->description,
                'workspace'        => getActiveWorkSpace(),
                'changed_by' => Auth::user()->name,
                'changed_at' => now()->format('H:i:s d-m-Y'),
            ];
            $user = User::find($termination->user_id);
            $this->saveLog($user, $termination, $changes, $action,$typeAction);

            event(new CreateTermination($request, $termination));
            try {
                $notification = CustomNotification::create(
                    [
                        'title' => 'Miễn nhiệm hợp đồng ',
                        'content' => 'Bạn vừa được miễn nhiệm hợp đồng',
                        'link' => "#",
                        'from' => $termination->created_by,
                        'send_to' => json_encode([$termination->id], JSON_NUMERIC_CHECK),
                        'type' => 'termination',
                    ]
                );
                UserNotifications::create(
                    [
                        'user_id' => $termination->user_id,
                        'notification_id' => $notification->id,
                        'is_read' => 0,
                    ]
                );
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
            $company_settings = getCompanyAllSetting();
            if (!empty($company_settings['Employee Termination']) && $company_settings['Employee Termination']  == true) {
                $User        = User::where('id', $termination->user_id)->where('workspace_id', '=',  getActiveWorkSpace())->first();
                $terminationtypes = TerminationType::where('id', '=', $request->termination_type)->where('workspace', getActiveWorkSpace())->first();

                $uArr = [
                    'employee_termination_name' => $User->name,
                    'notice_date' => $request->notice_date,
                    'termination_date' => $request->termination_date,
                    'termination_type' => !empty($terminationtypes) ? $terminationtypes->name : '',
                ];
                try {
                    $resp = EmailTemplate::sendEmailTemplate('Employee Termination', [$User->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }
                return redirect()->route('termination.index')->with('success', __('Termination  successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }

            return redirect()->route('termination.index')->with('success', __('Termination  successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return redirect()->back();
        return view('hrm::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Termination $termination)
    {
        if (\Auth::user()->isAbleTo('termination edit')) {
            if ($termination->created_by == creatorId() && $termination->workspace == getActiveWorkSpace()) {
                $employees   = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');
                $terminationtypes      = TerminationType::where('workspace', getActiveWorkSpace())->where('created_by', '=', creatorId())->get()->pluck('name', 'id');

                return view('hrm::termination.edit', compact('termination', 'employees', 'terminationtypes'));
            } else {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Termination $termination)
    {
        if (\Auth::user()->isAbleTo('termination edit')) {
            if ($termination->created_by == creatorId() && $termination->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'employee_id' => 'required',
                        'termination_type' => 'required',
                        'notice_date' => 'required|date',
                        'description' => 'required',
                        'termination_date' => 'required|after_or_equal:notice_date',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $employee = Employee::where('user_id', '=', $request->employee_id)->first();
                $originalData = $termination->getOriginal();

                if (!empty($employee)) {
                    $termination->employee_id = $employee->id;
                }
                $send_to = [];
                if ($termination->user_id !=  $request->employee_id) {
                    $send_to = [$request->employee_id, $termination->user_id];
                    $termination_user = User::find($termination->user_id);
                    $newUser = User::find($request->employee_id);
                    $notification_content= 'Đã thay đổi nhân viên từ ' . $termination_user->name . ' sang ' . $newUser->name;
                }else{
                    $send_to = [$termination->user_id];
                    $notification_content= 'Vừa cập nhật thông tin miễn nhiệm hợp đồng';
                }
                try {
                    $notification = CustomNotification::create(
                        [
                            'title' => 'Miễn nhiệm hợp đồng ',
                            'content' => $notification_content,
                            'link' => "#",
                            'from' => Auth::user()->id,
                            'send_to' => json_encode($send_to, JSON_NUMERIC_CHECK),
                            'type' => 'update_termination',
                        ]
                    );
                   foreach ($send_to as $key => $value) {
                        UserNotifications::create(
                            [
                                'user_id' => $value,
                                'notification_id' => $notification->id,
                                'is_read' => 0,
                            ]
                        );
                    }
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', __('Something went wrong please try again.'));
                }
                $termination->user_id          = $request->employee_id;
                $termination->termination_type = $request->termination_type;
                $termination->notice_date      = $request->notice_date;
                $termination->termination_date = $request->termination_date;
                $termination->description      = $request->description;
                $termination->save();
                $action = 'Cập nhật miễn nhiệm';
                $typeAction = 'update';
                $user = User::find($termination->user_id);
                $newData = $termination->fresh()->getAttributes();
                $changes = $this->getChanges($originalData, $newData);

                $this->saveLog($user, $termination, $changes, $action,$typeAction);



                event(new UpdateTermination($request, $termination));

                return redirect()->route('termination.index')->with('success', __('Termination successfully updated.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(Termination $termination)
    {
        if (Auth::user()->isAbleTo('termination delete')) {
            if ($termination->created_by == creatorId() && $termination->workspace == getActiveWorkSpace()) {

                $action = 'Xóa miễn nhiệm';
                $typeAction = 'delete';
                $nameType = TerminationType::find($termination->termination_type)->name;
                $userName = User::find($termination->user_id)->name;
                $user = User::find($termination->user_id);
                $changes = [
                    'name'             =>$userName,
                    'termination_type' => $nameType,
                    'changed_by' => Auth::user()->name,
                    'changed_at' => now()->format('H:i:s d-m-Y'),
                ];
                $this->saveLog($user, $termination, $changes, $action,$typeAction);
                $termination->delete();
                event(new DestroyTermination($termination));
                return redirect()->route('termination.index')->with('success', __('Termination successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function description($id)
    {
        if (Auth::user()->isAbleTo('termination description')) {
            $termination = Termination::find($id);
            return view('hrm::termination.description', compact('termination'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    private function getChanges($originalData = null, $newData = null)
    {
        $changes = [];
    
        foreach ($originalData as $key => $value) {
            if ($key === 'updated_at' || $key === 'created_at') {
                continue;
            }
    
            $oldValue = $originalData[$key];
            $newValue = $newData[$key];
    
            // Check if it's a select field
            if ($this->isSelectField($key)) {
                $oldName = $this->getNameFromDatabase($key, $oldValue);
                $newName = $this->getNameFromDatabase($key, $newValue);
            } else {
                $oldName = $oldValue;
                $newName = $newValue;
            }
    
            if ($oldName !== $newName) {
                $changes[$key] = [
                    'old' => $oldName,
                    'new' => $newName,
                    'changed_by' => Auth::user()->name,
                    'changed_at' => now()->format('H:i:s d-m-Y'),
                ];
            }
        }
    
        return $changes;
    }
    private function isSelectField($key)
    {
        $selectFields = ['termination_type', 'user_id'];
        return in_array($key, $selectFields);
    }

    private function getNameFromDatabase($key, $id)
    {
        switch ($key) {
            case 'termination_type':
                return TerminationType::find($id)->name;
            case 'user_id':
                return User::find($id)->name;
            default:
                return $id;
        }
    }
    private function saveLog($user, $employee, $changes, $action, $typeAction)
    {
        $logData = [
            'action_type' => $typeAction,
            'user_id' => $user->id,
            'user_type' => get_class($user),
            'employee_id' => $employee->id,
            'log_type' => $action,
            'remark' => json_encode(['changes' => $changes]),
        ];
        ActivityLogTermination::create($logData);
    }
}
