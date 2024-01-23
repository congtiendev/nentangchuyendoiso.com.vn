<?php

namespace Modules\Hrm\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Branch;
use Modules\Hrm\Entities\Department;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Entities\Transfer;
use Modules\Hrm\Events\CreateTransfer;
use Modules\Hrm\Events\DestroyTransfer;
use Modules\Hrm\Events\UpdateTransfer;
use App\Models\CustomNotification;
use App\Models\UserNotifications;
use App\Models\ActivityLogTransfer;
class TransferController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('transfer manage')) {
            if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $transfers     = Transfer::where('user_id', \Auth::user()->id)->where('workspace', getActiveWorkSpace())->with(['branch', 'department'])->get();
            } else {
                $transfers     = Transfer::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->with(['branch', 'department'])->get();
            }
            return view('hrm::transfer.index', compact('transfers'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
    
    public function activityLogTransfer()
    {
        if (Auth::user()->isAbleTo('transfer manage')) {

            $transfers = ActivityLogTransfer::latest()->get();

            return view('hrm::transfer.activityLog', compact('transfers'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('transfer create')) {
            $departments  = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $branches    = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $employees   = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');

            return view('hrm::transfer.create', compact('employees', 'branches', 'departments'));
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
        if (Auth::user()->isAbleTo('transfer create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'branch_id' => 'required',
                    'department_id' => 'required',
                    'transfer_date' => 'required|after:yesterday',
                    'description' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $transfer                = new Transfer();
            $employee = Employee::where('user_id', '=', $request->employee_id)->first();
            if (!empty($employee)) {
                $transfer->employee_id = $employee->id;
            }
            $transfer->user_id       = $request->employee_id;
            $transfer->branch_id     = $request->branch_id;
            $transfer->department_id = $request->department_id;
            $transfer->transfer_date = $request->transfer_date;
            $transfer->description   = $request->description;
            $transfer->workspace     = getActiveWorkSpace();
            $transfer->created_by    = creatorId();
            $transfer->save();

            $action = 'Tạo điều chuyển';
            $typeAction = 'store';
            $branchName = Branch::find($transfer->branch_id)->name;
            $departmentName = Department::find($transfer->department_id)->name;
            $userName = User::find($transfer->user_id)->name;
            $changes = [
                'user_id'          => $request->employee_id,
                'name'             =>$userName,
                'branchName' => $branchName,
                'departmentName'      => $departmentName,
                'workspace'        => getActiveWorkSpace(),
                'changed_by' => Auth::user()->name,
                'changed_at' => now()->format('H:i:s d-m-Y'),
            ];
            $user = User::find($transfer->user_id);
            $this->saveLog($user, $transfer, $changes, $action,$typeAction);


            event(new CreateTransfer($request, $transfer));

            $branch  = Branch::find($transfer->branch_id);
            $department = Department::find($transfer->department_id);
            try {
                $notification = CustomNotification::create(
                    [
                        'title' => 'Điều chuyển mới ',
                        'content' => 'Vừa điều chuyển bạn đến vị trí ' . $department->name . ' tại ' . $branch->name,
                        'link' => "#",
                        'from' => $transfer->created_by,
                        'send_to' => json_encode([$transfer->id], JSON_NUMERIC_CHECK),
                        'type' => 'transfer',
                    ]
                );
                UserNotifications::create(
                    [
                        'user_id' => $transfer->user_id,
                        'notification_id' => $notification->id,
                        'is_read' => 0,
                    ]
                );
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
            // $setings = Utility::settings();
            $company_settings = getCompanyAllSetting();
            if (!empty($company_settings['Employee Transfer']) && $company_settings['Employee Transfer']  == true) {

                $branch  = Branch::find($transfer->branch_id);
                $department = Department::find($transfer->department_id);
                $User        = User::where('id', $transfer->user_id)->where('workspace_id', '=',  getActiveWorkSpace())->first();
                $uArr = [
                    'transfer_name' => $User->name,
                    'transfer_date' => $request->transfer_date,
                    'transfer_branch' => $branch->name,
                    'transfer_department' => $department->name,
                    'transfer_description' => $request->description,
                ];
                try {

                    $resp = EmailTemplate::sendEmailTemplate('Employee Transfer', [$User->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }
                return redirect()->route('transfer.index')->with('success', __('Transfer  successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }

            return redirect()->route('transfer.index')->with('success', __('Transfer  successfully created.'));
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
    public function edit(Transfer $transfer)
    {
        if (Auth::user()->isAbleTo('transfer edit')) {
            if ($transfer->created_by == creatorId() && $transfer->workspace == getActiveWorkSpace()) {
                $departments  = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                $branches    = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                $employees   = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');

                return view('hrm::transfer.edit', compact('transfer', 'employees', 'departments', 'branches'));
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
    public function update(Request $request, Transfer $transfer)
    {
        if (Auth::user()->isAbleTo('transfer edit')) {
            if ($transfer->created_by == creatorId() && $transfer->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'employee_id' => 'required',
                        'branch_id' => 'required',
                        'department_id' => 'required',
                        'transfer_date' => 'required|date',
                        'description' => 'required',
                    ]
                );
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $employee = Employee::where('user_id', '=', $request->employee_id)->first();
                $originalData = $transfer->getOriginal();
                if (!empty($employee)) {
                    $transfer->employee_id = $employee->id;
                }
                $transfer->user_id       = $request->employee_id;
                $transfer->branch_id     = $request->branch_id;
                $transfer->department_id = $request->department_id;
                $transfer->transfer_date = $request->transfer_date;
                $transfer->description   = $request->description;
                $transfer->save();


                $action = 'Cập nhật điều chuyển';
                $typeAction = 'update';
                $user = User::find($transfer->user_id);
                $newData = $transfer->fresh()->getAttributes();
                $changes = $this->getChanges($originalData, $newData);

                $this->saveLog($user, $transfer, $changes, $action,$typeAction);


                event(new UpdateTransfer($request, $transfer));
                $branch  = Branch::find($transfer->branch_id);
                $department = Department::find($transfer->department_id);
                try {
                    $notification = CustomNotification::create(
                        [
                            'title' => 'Điều chuyển mới ',
                            'content' => 'Vừa điều chuyển bạn đến vị trí ' . $department->name . ' tại ' . $branch->name,
                            'link' => "#",
                            'from' => $transfer->created_by,
                            'send_to' => json_encode([$transfer->id], JSON_NUMERIC_CHECK),
                            'type' => 'transfer',
                        ]
                    );
                    UserNotifications::create(
                        [
                            'user_id' => $transfer->user_id,
                            'notification_id' => $notification->id,
                            'is_read' => 0,
                        ]
                    );
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', __('Something went wrong please try again.'));
                }
                return redirect()->route('transfer.index')->with('success', __('Transfer successfully updated.'));
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
    public function destroy(Transfer $transfer)
    {
        if (Auth::user()->isAbleTo('transfer delete')) {
            if ($transfer->created_by == creatorId() && $transfer->workspace == getActiveWorkSpace()) {
                 
                $action = 'Xóa điều chuyển';
                $typeAction = 'delete';
                $userName = User::find($transfer->user_id)->name;
                $user = User::find($transfer->user_id);
                $changes = [
                    'name'             =>$userName,
                    'changed_by' => Auth::user()->name,
                    'changed_at' => now()->format('H:i:s d-m-Y'),
                ];
                $this->saveLog($user, $transfer, $changes, $action,$typeAction);

                event(new DestroyTransfer($transfer));

                $transfer->delete();

                return redirect()->route('transfer.index')->with('success', __('Transfer successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
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
            case 'branch_id':
                return Branch::find($id)->name;
            case 'department_id':
                return Department::find($id)->name;
            case 'user_id':
                return User::find($id)->name;
            default:
                return $id;
        }
    }
    private function saveLog($user, $transfer, $changes, $action, $typeAction)
    {
        $logData = [
            'action_type' => $typeAction,
            'user_id' => $user->id,
            'user_type' => get_class($user),
            'employee_id' => $transfer->id,
            'log_type' => $action,
            'remark' => json_encode(['changes' => $changes]),
        ];
        ActivityLogTransfer::create($logData);
    }
}
