<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Award;
use Modules\Hrm\Entities\AwardType;
use Modules\Hrm\Events\CreateAwardType;
use Modules\Hrm\Events\DestroyAwardType;
use Modules\Hrm\Events\UpdateAwardType;
use App\Models\CustomNotification;
use App\Models\UserNotifications;
class AwardTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('awardtype manage')) {
            $awardtypes = AwardType::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();

            return view('hrm::awardtype.index', compact('awardtypes'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('awardtype create')) {
            return view('hrm::awardtype.create');
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
            event(new CreateAward($request, $award));
            try {
                $notification = CustomNotification::create(
                    [
                        'title' => 'Khen thưởng ',
                        'content' => 'Bạn vừa được khen thưởng một ' . $award->gift  . ' vào ngày ' . date('d-m-Y', strtotime($award->date)),
                        'link' => "#",
                        'from' => $award->created_by,
                        'send_to' => json_encode([$award->user_id], JSON_NUMERIC_CHECK),
                        'type' => 'application',
                    ]
                );
                UserNotifications::create(
                    [
                        'user_id' => $award->user_id,
                        'notification_id' => $notification->id,
                        'is_read' => 0,
                    ]
                );
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Something went wrong please try again.'));
            }
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
    public function edit(AwardType $awardtype)
    {
        if (Auth::user()->isAbleTo('awardtype edit')) {
            if ($awardtype->created_by == creatorId() && $awardtype->workspace == getActiveWorkSpace()) {
                return view('hrm::awardtype.edit', compact('awardtype'));
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
                $employee = Employee::where('user_id', '=', $request->employee_id)->first();
                if (!empty($employee)) {
                    $award->employee_id = $employee->id;
                }
                $send_to = [];
                if ($award->user_id !=  $request->employee_id) {
                    $send_to = [$request->employee_id, $award->user_id];
                    $award_user = User::find($award->user_id);
                    $newUser = User::find($request->employee_id);
                    $notification_content= 'Đã thay đổi nhân viên từ ' . $award_user->name . ' sang ' . $newUser->name;
                }else{
                    $send_to = [$award->user_id];
                    $notification_content= 'Vừa cập nhật thông tin khen thưởng';
                }

                try {
                    $notification = CustomNotification::create(
                        [
                            'title' => 'Khen thưởng ',
                            'content' => $notification_content,
                            'link' => "#",
                            'from' => Auth::user()->id,
                            'send_to' => json_encode($send_to, JSON_NUMERIC_CHECK),
                            'type' => 'update_award',
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

                $award->user_id     = $request->employee_id;
                $award->award_type  = $request->award_type;
                $award->date        = $request->date;
                $award->gift        = $request->gift;
                $award->description = $request->description;
                $award->save();
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
    public function destroy(AwardType $awardtype)
    {
        if (Auth::user()->isAbleTo('awardtype delete')) {
            if ($awardtype->created_by == creatorId() && $awardtype->workspace == getActiveWorkSpace()) {
                $awards     = Award::where('award_type', $awardtype->id)->where('workspace', getActiveWorkSpace())->get();
                if (count($awards) == 0) {
                    event(new DestroyAwardType($awardtype));

                    $awardtype->delete();
                } else {
                    return redirect()->route('awardtype.index')->with('error', __('This awardtype has award. Please remove the award from this awardtype.'));
                }

                return redirect()->route('awardtype.index')->with('success', __('Award Type successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }
}
