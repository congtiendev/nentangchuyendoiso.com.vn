<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMMS\Entities\Location;
use Illuminate\Support\Facades\Auth;
use Modules\CMMS\Entities\Component;
use Modules\CMMS\Entities\Workorder;
use Modules\CMMS\Entities\WosInvoice;
use Modules\CMMS\Entities\WosComment;
use Modules\CMMS\Entities\WorkOrderImage;
use Modules\CMMS\Entities\WosLogTime;
use Modules\CMMS\Entities\Part;
use Modules\CMMS\Entities\CmmsPos;
use App\Models\User;
use App\Models\EmailTemplate;
use DB;
use Modules\CMMS\Events\CreateWorkorder;
use Modules\CMMS\Events\DestroyWorkorder;
use Modules\CMMS\Events\UpdateWorkorder;
use App\Models\CustomNotification;
use App\Models\UserNotifications;

class WorkorderController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('workorder manage')) {
            $objUser            = Auth::user();
            $currentLocation = Location::userCurrentLocation();
            $locations = Location::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');

            if ($objUser->type == "company") {
                $work_order = WorkOrder::with('getLocation')->where(['company_id' => creatorId(), 'workspace' => getActiveWorkSpace(), 'location_id' => $currentLocation, 'status' => 1])->get();

                return view('cmms::workorder.index', compact('currentLocation', 'locations', 'work_order'));
            } else {
                $total_work_order = WorkOrder::with('getLocation')->whereRaw("FIND_IN_SET(" . $objUser->id . ",sand_to)")->where('location_id', $currentLocation)->where('status', 1)->get();
                return view('cmms::workorder.index', compact('currentLocation', 'locations', 'total_work_order'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        if (Auth::user()->isAbleTo('workorder create')) {
            $currentLocation = Location::userCurrentLocation();
            $components_id = $request->components_id;
            $Components = Component::where(['company_id' => creatorId(), 'location_id' => $currentLocation, 'is_active' => 1])->get()->pluck('name', 'id');
            $user = User::where('created_by', creatorId())->where('workspace_id', getActiveWorkSpace())->get()->pluck('name', 'id');
            $locations = Location::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');;

            return view('cmms::workorder.create', compact('Components', 'user', 'currentLocation', 'locations', 'components_id'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (\Auth::user()->isAbleTo('workorder create')) {

            $validator = \Validator::make(
                $request->all(),
                [
                    'date' => 'required|date|after_or_equal:' . date('Y-m-d')

                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $objUser   = Auth::user();

            $currentlocation = Location::userCurrentLocation();

            if ($currentlocation == 0) {
                return redirect()->back()->with('error', __('Current location is not available.'));
            }

            $workorder = Workorder::create([
                'wo_id'         => time(),
                'components_id' => $request->components,
                'wo_name'       => $request->wo_name,
                'instructions'  => $request->instructions,
                'tags'          => $request->tags,
                'priority'      => $request->priority,
                'date'          => $request->date,
                'time'          => $request->time,
                'sand_to'       => !empty($request->user) ? implode(',', $request->user) : $objUser->id,
                'work_status'   => 'Open',
                'location_id'   => $request->location,
                'created_by'    => $objUser->id,
                'company_id'    => creatorId(),
                'workspace'     => getActiveWorkSpace(),
            ]);


            //chuyen $request->user thanh mang

            event(new CreateWorkorder($request, $workorder));
            try {
                $notification = CustomNotification::create([
                    'title' => 'Công việc mới',
                    'content' => 'đã chỉ định bạn thực hiện ' . $request->wo_name,
                    'link' => route('workorder.show', $workorder->id),
                    'from' => $objUser->id,
                    'send_to' => json_encode(array_map('intval', $request->user)),
                    'type' => 'new_workorder',
                ]);
                foreach ($request->user as $user_id) {
                    UserNotifications::create([
                        'user_id' => $user_id,
                        'notification_id' => $notification->id,
                        'is_read' => 0
                    ]);
                }
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
            if ($workorder) {
                if (!empty(company_setting('Work Order Assigned')) && company_setting('Work Order Assigned')  == true) {
                    $User        = User::where('id', $request->user)->where('workspace_id', '=',  getActiveWorkSpace())->first();
                    $components = Component::find($request->components);

                    $uArr = [
                        'work_order_id' => $workorder->wo_id,
                        'components' => $components->name,
                        'priority' => $workorder->priority,
                        'work_order_due_date' => $workorder->date,
                    ];
                    try {
                        if ($User) {
                            $resp = EmailTemplate::sendEmailTemplate('Work Order Assigned', [$User->email], $uArr);
                        }
                    } catch (\Exception $e) {
                        $resp['error'] = $e->getMessage();
                    }

                    return redirect()->back()->with('success', __('Work order  successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                }

                return redirect()->back()->with('success', __('Work order created successfully.'));
            } else {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }



    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (\Auth::user()->isAbleTo('workorder manage')) {
            $Workorder = WorkOrder::where(['id' => $id])->first();

            if (!is_null($Workorder)) {

                $ComponentsField = DB::table('components_field_values')->leftJoin('components_fields', 'components_field_values.field_id', '=', 'components_fields.id')->where(['record_id' => $id])->get();


                $parts = DB::table('workorders')->leftJoin('parts', function ($join) {
                    $join->whereRaw(DB::raw("find_in_set(parts.id,workorders.parts_id)"));
                })->where('parts.id', '!=', null)->where('workorders.id', $Workorder->id)->get();


                $Workorder_file = WorkOrderImage::where(['wo_id' => $id])->get();

                $woslogtime = DB::table('wos_log_times')
                    ->join('users', 'wos_log_times.user_id', '=', 'users.id')
                    ->select('wos_log_times.*', 'users.*', 'wos_log_times.id as wos_lt')
                    ->where(['wos_log_times.wo_id' => $id])
                    ->get();

                $wo_pos = DB::table('cmms_pos')
                    ->join('users', 'cmms_pos.user_id', '=', 'users.id')
                    ->join('suppliers', 'cmms_pos.supplier_id', '=', 'suppliers.id')
                    ->select(DB::raw('cmms_pos.*, users.name as user_name, suppliers.name as supplier_name'))
                    ->where('cmms_pos.wo_id', $id)
                    ->get();


                $wosinvoice =  WosInvoice::where(['wo_id' => $id])->get();

                $woscomment =  WosComment::where(['wo_id' => $id])->get();

                $components_data =  Component::find($Workorder->components_id);

                $sanddata =  User::whereIn('id', explode(',', $Workorder->sand_to))->get()->pluck('name');
                $Sand_data = [];
                if (count($sanddata) > 0) {
                    foreach ($sanddata as $datasand) {
                        $Sand_data[] = $datasand;
                    }
                }

                $Workorder_tag = explode(',', $Workorder->tags);

                $chartData = $this->getChartData(['duration' => 'year', 'workorder_id' => $id]);

                $hours = DB::table("wos_log_times")->where('wo_id', $id)->sum('hours');
                $minutes = DB::table("wos_log_times")->where('wo_id', $id)->sum('minute');
                $min_hours = number_format($minutes / 60);


                if ($woslogtime) {
                    $total_spend = (int)$hours + (int)$min_hours;
                } else {
                    $total_spend = 0;
                }

                $arrPartsper = [];
                $arrPartsLabel = ['Not Purchased ', 'Purchased '];
                $total_parts = Part::where('location_id', Auth::user()->location_id)->count();
                $WorkorderParts = CmmsPos::where('location_id', Auth::user()->location_id)->where('wo_id', $id)->count();

                if ($total_parts == 0) {
                    $tp = 0.0;
                    $purchase = 0;
                } else {
                    $purchase = round($WorkorderParts * 100 / $total_parts, 2);
                }

                $arrPartsper[0] = 100 - $purchase;
                $arrPartsper[1] = $purchase;

                return view('cmms::workorder.view', compact('Workorder',  'WorkorderParts', 'wo_pos', 'woscomment', 'Workorder_file', 'ComponentsField', 'parts',  'chartData', 'Workorder_tag', 'woslogtime', 'wosinvoice', 'components_data', 'Sand_data',  'arrPartsper', 'arrPartsLabel'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (Auth::user()->isAbleTo('workorder edit')) {
            $currentLocation = Location::userCurrentLocation();
            $location = Location::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            $Component = Component::where(['company_id' => creatorId(), 'location_id' => $currentLocation, 'is_active' => 1])->get()->pluck('name', 'id');
            $user = User::where(['created_by' => creatorId(), 'workspace_id' => getActiveWorkSpace()])->get()->pluck('name', 'id');
            $workorder = WorkOrder::where('workspace', getActiveWorkSpace())->where('id', $id)->first();
            return view('cmms::workorder.edit', compact('currentLocation', 'location', 'workorder', 'Component', 'user'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $objUser = Auth::user();
        if (\Auth::user()->isAbleTo('workorder edit')) {
            $currentLocation = Location::userCurrentLocation();

            if ($currentLocation == 0) {
                return redirect()->back()->with('error', __('Current location is not available.'));
            }
            $SandTo = WorkOrder::where(['id' => $id])->first();
            $workorder['components_id']    = $request->components_id;
            $workorder['wo_name']      = $request->wo_name;
            $workorder['instructions'] = $request->instructions;
            $workorder['tags']         = $request->tags;
            $workorder['priority']     = $request->priority;
            $workorder['date']         = $request->date;
            $workorder['time']         = $request->time;
            $workorder['sand_to']      = !empty($request->user) ? implode(",", $request->user) : '';
            $workorder['location_id']  = $request->location;
            $workorder['created_by']   = $objUser->id;
            $workorder['company_id']   = creatorId();
            $workorder['workspace']   = getActiveWorkSpace();
    
            $workorder = WorkOrder::where('id', $id)->update($workorder);
            event(new UpdateWorkorder($request, $workorder));
            $notification_content = array(); 
            if ($request->wo_name != $SandTo->wo_name) {
                $notification_content[] = "Đã đổi tên " . $SandTo->wo_name . " thành " . $request->wo_name . ". ";
            }
            $user_delete = array_diff(explode(',', $SandTo->sand_to), $request->user);
            if (count($user_delete) > 0) {
                $user_delete = User::whereIn('id', $user_delete)->get()->pluck('name');
                $notification_content[] = "Đã xóa " . implode(',', $user_delete->toArray()) . " khỏi " . $SandTo->wo_name . ". ";
            }

            $user_add = array_diff($request->user, explode(',', $SandTo->sand_to));
            if (count($user_add) > 0) {
                $user_add = User::whereIn('id', $user_add)->get()->pluck('name');
                $notification_content[] = "Đã thêm " . implode(',', $user_add->toArray()) . " vào " . $SandTo->wo_name . ". ";
            }

            if ($request->components_id != $SandTo->components_id) {
                $component = Component::find($request->components_id);
                $notification_content[] = "Đã thay đổi thiết bị " . $SandTo->wo_name . "   từ " . $SandTo->components->name . " thành " . $component->name . ". ";
            }

            if ($request->priority != $SandTo->priority) {
                $notification_content[] = "Đã thay đổi độ ưu tiên " . $SandTo->wo_name . " từ " . $SandTo->priority . " thành " . $request->priority . ". ";
            }

            if ($request->date != $SandTo->date) {
                $notification_content[] = "Đã thay đổi ngày " . $SandTo->wo_name . " từ " . $SandTo->date . " thành " . $request->date . ". ";
            }

            if ($request->time != $SandTo->time) {
                $notification_content[] = "Đã thay đổi thời gian " . $SandTo->wo_name . " từ " . $SandTo->time . " thành " . $request->time . ". ";
            }

            if ($request->instructions != $SandTo->instructions) {
                $notification_content[] = "Đã thay đổi hướng dẫn " . $SandTo->wo_name . " từ " . $SandTo->instructions . " thành " . $request->instructions . ". ";
            }

            if ($request->tags != $SandTo->tags) {
                $notification_content[] = "Đã thay đổi thẻ " . $SandTo->wo_name . " từ " . $SandTo->tags . " thành " . $request->tags . ". ";
            }
            foreach ($notification_content as $value) {
                try {
                    $notification = CustomNotification::create([
                        'title' => 'Cật nhật công việc',
                        'content' => $value,
                        'link' => route('workorder.show', $id),
                        'from' => $objUser->id,
                        'send_to' => json_encode(array_map('intval', $request->user)),
                        'type' => 'edit_workorder',
                    ]);
            
                    $userNotifications = [];
                    foreach ($request->user as $user_id) {
                        $userNotifications[] = [
                            'user_id' => $user_id,
                            'notification_id' => $notification->id,
                            'is_read' => 0,
                        ];
                    }
            
                    UserNotifications::insert($userNotifications);
                } catch (\Exception $e) {
                    return redirect()->back()->with('error', __('Something went wrong.'));
                }
            }
            if ($workorder) {
                return redirect()->back()->with('success', __('Work order update successfully.'));
            } else {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (\Auth::user()->isAbleTo('workorder delete')) {
            $workorder = WorkOrder::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            if ($workorder) {
                $workorder->delete();

                event(new DestroyWorkorder($workorder));
            }
            return redirect()->back()->with('success', __('Work Order successfully deleted .'));
        } else {
            return redirect()->back()->with('error', __("Permission Denied"));
        }
    }

    public function getChartData($arrParam)
    {
        //work order detail page in curve chart for recent order
        $arrDuration = [];
        $workorder_id = $arrParam['workorder_id'];
        if ($arrParam['duration']) {
            if ($arrParam['duration'] == 'year') {
                for ($i = 0; $i < 12; $i++) {
                    $arrDuration[] = date('F', strtotime("+$i Months"));
                }
            }
        }
        $arrTask          = [];
        $arrTask['label'] = [];
        $arrTask['data']  = [];
        $final_data = 0;
        foreach ($arrDuration as $date => $label) {
            $year = date('Y');

            $data = WosLogTime::select(\DB::raw('sum(hours)+sum(minute/60) as total_hours'))->where('wo_id', $workorder_id)->whereMonth('created_at', date('m', strtotime($label)))->whereYear('created_at', $year)->first();
            if ($data->total_hours != null) {
                $final_data = number_format($data->total_hours, 0);
            } else {
                $final_data = 0;
            }
            $arrTask['label'][] = $label;
            $arrTask['data'][]  = $final_data;
        }
        return $arrTask;
    }

    public function fileUpload($id, Request $request)
    {
        $objUser = Auth::user();
        $currentlocation = Location::userCurrentLocation(); {
            $file_name = $request->file->getClientOriginalName();
            $file_path = $request->lead_id . "_" . md5(time()) . "_" . $request->file->getClientOriginalName();
            $dir = 'workorder_files/';

            $path = upload_file($request, 'file', $file_path, $dir, []);
            if ($path['flag'] == 1) {
                $file = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }

            $file = WorkOrderImage::create(
                [
                    'wo_id'       => $id,
                    'image'       => $file_path,
                    'location_id' => $currentlocation,
                    'created_by'  => $objUser->id,
                    'company_id'  => creatorId(),
                    'workspace'   => getActiveWorkSpace(),
                ]
            );
            $return               = [];
            $return['is_success'] = true;
            $return['download']   = route(
                'workorder.file.download',
                [$file->id]
            );

            $return['delete']     = route(
                'workorder.file.delete',
                [$file->id]
            );


            $return               = [];
            $return['is_success'] = true;
            $return['status'] = 1;
            $return['success_msg'] = __('Attachment Created Successfully') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : '');
        }
        return response()->json($return);
    }


    public function fileDownload($id)
    {
        $file = WorkOrderImage::find($id);
        if ($file) {
            $file_path = base_path()  . $file->image;
            $filename  = $file->file_name;

            return \Response::download(
                $file_path,
                $filename,
                [
                    'Content-Length: ' . filesize($file_path),
                ]
            );
        } else {
            return redirect()->back()->with('error', __('File is not exist.'));
        }
    }



    public function fileDelete($id)
    {
        $file = WorkOrderImage::find($id);
        if ($file) {
            $file_path = 'workorder_files/' . $file->image;
            $file->delete();

            return response()->json(['is_success' => true], 200);
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('File is not exist.'),
                ],
                200
            );
        }
    }

    public function taskcomplete(Request $request)
    {
        $task_id = $request->task_id;
        return view('cmms::workorder.taskcomplete', compact('task_id'));
    }

    public function updatetaskcomplete(Request $request)
    {
        // complete task
        $task_id = $request->task_id;

        $woscomment['hours']        = $request->hours;
        $woscomment['minute']       = $request->minute;
        $woscomment['status']       = 2;
        $woscomment['work_status']  = null;
        $woscomments = WorkOrder::where('id', $task_id)->update($woscomment);

        if (!empty($woscomments)) {
            return redirect()->back()->with(['success' => __('Task Complete Successfully.'), 'tab-status' => 'comment']);
        } else {
            return redirect()->back()->with(['error' => __('Something went wrong.'), 'tab-status' => 'comment']);
        }
    }

    public function taskreopen($id)
    {
        //reopen task
        $woscomment['hours']        = null;
        $woscomment['minute']       = null;
        $woscomment['status']       = 1;
        $woscomment['work_status']  = 'Open';
        $woscomments = WorkOrder::where('id', $id)->update($woscomment);

        if (!empty($woscomments)) {
            return redirect()->back()->with(['success' => __('Task Reopen Successfully.'), 'tab-status' => 'comment']);
        } else {
            return redirect()->back()->with(['error' => __('Something went wrong.'), 'tab-status' => 'comment']);
        }
    }

    public function workstatus(Request $request)
    {

        $workorder['work_status']  = $request->work_status;
        $workorder = WorkOrder::where('id', $request->wos_id)->update($workorder);
    }

    public function completetask()
    {
        $objUser = Auth::user();
        $currentLocation = Location::userCurrentLocation();
        $locations = Location::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');

        if ($objUser->type == "company") {
            $work_order = WorkOrder::where(['company_id' => creatorId(), 'location_id' => $currentLocation, 'workspace' => getActiveWorkSpace(), 'status' => 2])->get();
            return view('cmms::workorder.complete', compact('work_order', 'locations', 'currentLocation'));
        } else {
            $work_order = WorkOrder::whereRaw("FIND_IN_SET(" . $objUser->id . ",sand_to)")->where('status', 2)->get();

            $total_work_order = $work_order;

            return view('cmms::workorder.complete', compact('total_work_order', 'locations', 'currentLocation'));
        }
    }

    public function wosimport()
    {
        if (Auth::user()->isAbleTo('workorder import')) {
            return view('cmms::workorder.import');
        } else {
            return redirect()->back()->with('error', __('permission denied'));
        }
    }


    public function wosimportCreate(Request $request)
    {
        if (Auth::user()->isAbleTo('workorder import')) {
            session_start();

            $error = '';

            $html = '';

            if ($request->file->getClientOriginalName() != '') {
                $file_array = explode(".", $request->file->getClientOriginalName());

                $extension = end($file_array);
                if ($extension == 'csv') {
                    $file_data = fopen($request->file->getRealPath(), 'r');

                    $file_header = fgetcsv($file_data);
                    $html .= '<table class="table table-bordered"><tr>';

                    for ($count = 0; $count < count($file_header); $count++) {
                        $html .= '
                                <th>
                                    <select name="set_column_data" class="form-control set_column_data" data-column_number="' . $count . '">
                                    <option value="">Set Count Data</option>
                                    <option value="wo_id">wo_id</option>
                                    <option value="wo_name">WorkOrder_Name</option>
                                    <option value="priority">Priority</option>
                                    <option value="date">Date</option>
                                    <option value="time">Time</option>
                                    <option value="instructions">Instructions</option>
                                    <option value="work_status">work_status</option>
                                    <option value="tags">Tags</option>
                                    </select>
                                </th>
                                ';
                    }
                    $html .= '</tr>';
                    $limit = 0;
                    while (($row = fgetcsv($file_data)) !== false) {
                        $limit++;

                        $html .= '<tr>';

                        for ($count = 0; $count < count($row); $count++) {
                            $html .= '<td>' . $row[$count] . '</td>';
                        }

                        $html .= '</tr>';

                        $temp_data[] = $row;
                    }
                    $_SESSION['file_data'] = $temp_data;
                } else {
                    $error = 'Only <b>.csv</b> file allowed';
                }
            } else {

                $error = 'Please Select CSV File';
            }
            $output = array(
                'error' => $error,
                'output' => $html,
            );

            echo json_encode($output);
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function fileImportModal()
    {
        if (Auth::user()->isAbleTo('workorder import')) {
            return view('cmms::workorder.import_modal');
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function workorderImportdata(Request $request)
    {
        if (Auth::user()->isAbleTo('workorder import')) {
            session_start();
            $html = '<h3 class="text-danger text-center">Below data is not inserted</h3></br>';
            $flag = 0;
            $html .= '<table class="table table-bordered"><tr>';
            $file_data = $_SESSION['file_data'];
            $location = Location::userCurrentLocation();
            unset($_SESSION['file_data']);

            $user = \Auth::user();
            foreach ($file_data as $row) {
                $workorder = Workorder::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->where('location_id', $location)->get();

                try {

                    Workorder::create([
                        'wo_id' => $row[$request->wo_id],
                        'created_by' => $user->id,
                        'wo_name' => $row[$request->wo_name],
                        'priority' => $row[$request->priority],
                        'date' => $row[$request->date],
                        'time' => $row[$request->time],
                        'instructions' => $row[$request->instructions],
                        'work_status' => $row[$request->work_status],
                        'tags' => $row[$request->tags],
                        'location_id' => $location,
                        'company_id' => creatorId(),
                        'workspace' => getActiveWorkSpace(),
                    ]);
                } catch (\Exception $e) {
                    $flag = 1;
                    $html .= '<tr>';

                    $html .= '<td>' . $row[$request->wo_id] . '</td>';
                    $html .= '<td>' . $row[$request->wo_name] . '</td>';
                    $html .= '<td>' . $row[$request->priority] . '</td>';
                    $html .= '<td>' . $row[$request->date] . '</td>';
                    $html .= '<td>' . $row[$request->time] . '</td>';
                    $html .= '<td>' . $row[$request->instructions] . '</td>';
                    $html .= '<td>' . $row[$request->work_status] . '</td>';
                    $html .= '<td>' . $row[$request->tags] . '</td>';

                    $html .= '</tr>';
                }
            }

            $html .= '
                            </table>
                            <br />
                            ';
            if ($flag == 1) {

                return response()->json([
                    'html' => true,
                    'response' => $html,
                ]);
            } else {
                return response()->json([
                    'html' => false,
                    'response' => 'Data Imported Successfully',
                ]);
            }
        } else {
            return redirect()->back()->with('error', 'permission Denied');
        }
    }

    public function getcomponent(Request $request)
    {
        if ($request->location_id == 0) {
            $component = \Modules\CMMS\Entities\Component::get()->pluck('name', 'id')->toArray();
        } else {
            $component  = \Modules\CMMS\Entities\Component::where('location_id', $request->location_id)->get()->pluck('name', 'id');
        }

        return response()->json($component);
    }

    public function componentsedit($id)
    {
        $currentlocation = Location::userCurrentLocation();

        $component = Component::where(['company_id' => creatorId(), 'location_id' => $currentlocation, 'is_active' => 1])->get()->pluck('name', 'id');
        return view('cmms::workorder.componentedit', compact('component', 'id'));
    }

    public function componentsupdate(Request $request)
    {
        $workorder['components_id']   = $request->component_id;
        $workorder = WorkOrder::where('id', $request->wo_id)->update($workorder);

        if ($workorder) {
            return redirect()->back()->with(['success' => __('Component update successfully.'), 'tab-status' => 'asset']);
        } else {
            return redirect()->back()->with(['error', __('Something went wrong.'), 'tab-status' => 'asset']);
        }
    }
}
