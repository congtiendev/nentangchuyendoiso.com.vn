<?php

namespace Modules\Hrm\Http\Controllers;

use App\Models\EmailTemplate;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Award;
use Modules\Hrm\Entities\AwardType;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Events\CreateAward;
use Modules\Hrm\Events\DestroyAward;
use Modules\Hrm\Events\UpdateAward;
use App\Models\ActivityLogAward;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('award manage')) {
            if (!in_array(Auth::user()->type, Auth::user()->not_emp_type)) {
                $awards     = Award::where('user_id', Auth::user()->id)->where('workspace', getActiveWorkSpace())->get();
            } else {
                $awards     = Award::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->with(['users', 'awardType'])->get();
            }

            return view('hrm::award.index', compact('awards'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function activityLogAward()
    {
        if (Auth::user()->isAbleTo('transfer manage')) {

            $awards = ActivityLogAward::latest()->get();

            return view('hrm::award.activityLog', compact('awards'));
        }
    }


    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('award create')) {
            $employees = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');
            $awardtypes = AwardType::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('hrm::award.create', compact('employees', 'awardtypes'));
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
        if (Auth::user()->isAbleTo('award create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'employee_id' => 'required',
                    'award_type' => 'required',
                    'date' => 'required|after:yesterday',
                    'gift' => 'required',
                    'description' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $award              = new Award();
            $employee = Employee::where('user_id', '=', $request->employee_id)->first();
            if (!empty($employee)) {
                $award->employee_id = $employee->id;
            }
            $award->user_id     = $request->employee_id;
            $award->award_type  = $request->award_type;
            $award->date        = $request->date;
            $award->gift        = $request->gift;
            $award->description =  $request->description;
            $award->workspace   = getActiveWorkSpace();
            $award->created_by  = creatorId();
            $award->save();

            $action = 'Tạo khen thưởng';
            $typeAction = 'store';
            $userName = User::find($award->user_id)->name;
            $award_type = AwardType::find($award->award_type)->name;
            $changes = [
                'user_id'          => $request->employee_id,
                'name'             =>$userName,
                'award_type'       =>$award_type,
                'gift'             =>$award->gift,
                'workspace'        => getActiveWorkSpace(),
                'changed_by' => Auth::user()->name,
                'changed_at' => now()->format('H:i:s d-m-Y'),
            ];
            $user = User::find($award->user_id);
            $this->saveLog($user, $award, $changes, $action,$typeAction);

            event(new CreateAward($request, $award));

            $awardtype = AwardType::find($request->award_type);
            $company_settings = getCompanyAllSetting();

            if (!empty($company_settings['New Award']) && $company_settings['New Award']  == true) {
                $User        = User::where('id', $request->employee_id)->where('workspace_id', '=',  getActiveWorkSpace())->first();

                $uArr = [
                    'award_name' => $User->name,
                    'award_date' => $award->date,
                    'award_type' => $awardtype->name,
                ];
                try {
                    $resp = EmailTemplate::sendEmailTemplate('New Award', [$User->email], $uArr);
                } catch (\Exception $e) {
                    $resp['error'] = $e->getMessage();
                }
                return redirect()->route('award.index')->with('success', __('Award  successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            }
            return redirect()->route('award.index')->with('success', __('Award  successfully created.'));
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
    public function edit(Award $award)
    {
        if (Auth::user()->isAbleTo('award edit')) {
            if ($award->created_by == creatorId() && $award->workspace == getActiveWorkSpace()) {
                $employees = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->emp()->get()->pluck('name', 'id');
                $awardtypes = AwardType::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                return view('hrm::award.edit', compact('award', 'awardtypes', 'employees'));
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
    public function update(Request $request, Award $award)
    {
        if (Auth::user()->isAbleTo('award edit')) {
            if ($award->created_by == creatorId() && $award->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'employee_id' => 'required',
                        'award_type' => 'required',
                        'date' => 'required|after:' . date('Y-m-d'),
                        'gift' => 'required',
                        'description' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }
                $originalData = $award->getOriginal();

                $employee = Employee::where('user_id', '=', $request->employee_id)->first();
                if (!empty($employee)) {
                    $award->employee_id = $employee->id;
                }
                $award->user_id     = $request->employee_id;
                $award->award_type  = $request->award_type;
                $award->date        = $request->date;
                $award->gift        = $request->gift;
                $award->description = $request->description;
                $award->save();


                $action = 'Cập nhật khen thưởng';
                $typeAction = 'update';
                $user = User::find($award->user_id);
                $newData = $award->fresh()->getAttributes();
                $changes = $this->getChanges($originalData, $newData);
                $this->saveLog($user, $award, $changes, $action,$typeAction);

                event(new UpdateAward($request, $award));
                return redirect()->route('award.index')->with('success', __('Award successfully updated.'));
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
    public function destroy(Award $award)
    {
        if (Auth::user()->isAbleTo('award delete')) {
            if ($award->created_by == creatorId() && $award->workspace == getActiveWorkSpace()) {
                event(new DestroyAward($award));
                $action = 'Xóa điều chuyển';
                $typeAction = 'delete';
                $userName = User::find($award->user_id)->name;
                $user = User::find($award->user_id);
                $changes = [
                    'name'             =>$userName,
                    'changed_by' => Auth::user()->name,
                    'changed_at' => now()->format('H:i:s d-m-Y'),
                ];
                $this->saveLog($user, $award, $changes, $action,$typeAction);

                $award->delete();

                return redirect()->route('award.index')->with('success', __('Award successfully deleted.'));
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
        $selectFields = ['award_type', 'user_id'];
        return in_array($key, $selectFields);
    }

    private function getNameFromDatabase($key, $id)
    {
        switch ($key) {
            case 'user_id':
                return User::find($id)->name;
            case 'award_type':
                return AwardType::find($id)->name;
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
        ActivityLogAward::create($logData);
    }
}
