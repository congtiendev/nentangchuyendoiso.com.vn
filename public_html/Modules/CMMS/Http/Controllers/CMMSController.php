<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\CMMS\Entities\Location;
use Modules\CMMS\Entities\Workorder;
use Modules\CMMS\Entities\Component;
use Modules\CMMS\Entities\Pms;
use App\Models\User;

class CMMSController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
   

        $objUser = Auth::user();
        $currentlocation = Location::userCurrentLocation();

        $locations = Location::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->get()->pluck('name', 'id');

        if (Auth::check()) {
         if ($objUser->type == "company") {

                $open_workOrder     = Workorder::where('status', 1)->where('location_id', $currentlocation)->count();
                $complete_workOrder = $completeTask = Workorder::where('status', 2)->where('location_id', $currentlocation)->count();

                $total_components      = Component::where('is_active', 1)->where('location_id', $currentlocation)->count();
                $total_pms          = Pms::where('is_active', 1)->where('location_id', $currentlocation)->count();

                $chartData          = $this->getChartData(['duration' => 'week', 'current_location' => $currentlocation]);
                $totalProject       = WorkOrder::where("company_id", "=", $objUser->id)->where('location_id', '=', $currentlocation)->count();

                $projectProcess     = WorkOrder::where("company_id", "=", $objUser->id)->where('location_id', '=', $currentlocation)->groupBy('status')->selectRaw('count(id) as count ')->pluck('count');
                
                $arrProcessPer   = [];
                $arrProcessLabel = [];
                
                if (count($projectProcess) <= 0) {
                    $arrProcessLabel[] = ['pending'];
                    $arrProcessLabel[] = ['Complete'];
                    
                } else {
                    
                    foreach ($projectProcess as $lable => $process) {
                        if ($lable == 1) {

                            $arrProcessLabel[] = 'Complete';
                        } else {
                            $arrProcessLabel[] = 'Pandding';
                        }

                        if ($totalProject == 0) {
                            $arrProcessPer[] = 0.00;
                        } else {
                            $arrProcessPer[] = round(($process * 100) / $totalProject, 2);
                        }
                    }
                }
                $arrProcessClass = [
                    'text-success',
                    'text-primary',
                ];

                $tasks        = WorkOrder::where('status', 2)->where('location_id', $currentlocation)->get();
                // $completeTask = WorkOrder::where('status', 2)->where('location_id', $currentlocation)->count();
                $totalTask    = WorkOrder::where('location_id', $currentlocation)->count();

                // $users = User::find(creatorId());
                return view('cmms::dashboard.dashboard', compact('open_workOrder','complete_workOrder', 'total_components', 'total_pms', 'arrProcessPer', 'arrProcessLabel', 'arrProcessClass', 'currentlocation', 'chartData', 'tasks', 'completeTask', 'totalTask' , 'locations'));

            } else if ($objUser->user_type != "company") {
                $user_id            = Auth::user()->id;
                $assign_work_order  = WorkOrder::whereRaw("FIND_IN_SET(" . $user_id . ",sand_to)")->where('location_id', '=', $currentlocation)->where('created_by','!=',$user_id)->count();
                $created_work_order = WorkOrder::where("created_by", Auth::user()->id)->where('location_id', '=', $currentlocation)->get();

                $complete_workorder  = WorkOrder::whereRaw("FIND_IN_SET(" . $user_id . ",sand_to)")->where('status', '2')->where('location_id', '=', $currentlocation)->count();
                $total_complete_order = ($complete_workorder );

                $open_workorder     = WorkOrder::where('created_by', $objUser->id)->where('location_id', '=', $currentlocation)->count();

                $chartData          = $this->getChartData(['duration' => 'week', 'current_location' => $currentlocation]);

                $openproject = WorkOrder::where('location_id', $currentlocation)->where("sand_to", "=", $objUser->id)->count();
                $totalProject       =  $openproject;

                $projectProcess     = WorkOrder::whereRaw("FIND_IN_SET(" . $user_id . ",sand_to)")->where('location_id', '=', $currentlocation)->groupBy('status')->selectRaw('count(id) as count ')->pluck('count')->toArray();

                    $arrProcessPer   = [];
                    $arrProcessLabel = [];
                    if (count($projectProcess) <= 0) {
                        $arrProcessLabel[] = ['pending'];
                        $arrProcessLabel[] = ['Complete'];
                        
                    } else {
                        
                        foreach ($projectProcess as $lable => $process) {
                            if ($lable == 1) {
                                $arrProcessLabel[] = 'Complete';
                            } else {
                                $arrProcessLabel[] = 'Pandding';
                            }
    
                            if ($totalProject == 0) {
                                $arrProcessPer[] = 0.00;
                            } else {
                                $arrProcessPer[] = round(($process * 100) / $totalProject, 2);
                            }
                        }
                    }
                
                $arrProcessClass = [
                    'text-success',
                    'text-primary',
                ];

                $complete_open_order = WorkOrder::whereRaw("FIND_IN_SET(" . $objUser->id . ",sand_to)")->where('location_id',$currentlocation)->where('status', 2)->get();

                $tasks = $complete_open_order;

                
                $completeopenTask = WorkOrder::where('status', 2)->where('location_id', $currentlocation)->where("sand_to", "=", $objUser->id)->count();
                $completeTask = $completeopenTask ;
                $assignTask    = WorkOrder::where('location_id', $currentlocation)->where("sand_to", "=", $objUser->id)->count();
                $totalTask = $assignTask ;

                return view('cmms::dashboard.user_dashboard', compact('assign_work_order', 'currentlocation' , 'locations','tasks', 'completeTask', 'totalTask', 'chartData', 'arrProcessPer', 'arrProcessLabel', 'arrProcessClass', 'created_work_order', 'open_workorder' , 'total_complete_order'));

            }

        } else {
            return redirect()->route('login');
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('cmms::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('cmms::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function getChartData($arrParam)
    {
       
        $currentlocation = $arrParam['current_location'];
        $arrDuration = [];
        if ($arrParam['duration']) {
            if ($arrParam['duration'] == 'week') {
                $previous_week = strtotime("-1 week +1 day");
                for ($i = 0; $i < 7; $i++) {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-M', $previous_week);
                    $previous_week = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }
        $arrTask = [];
        $arrTask['label'] = [];
        $arrTask['data'] = [];
        foreach ($arrDuration as $date => $label) {

        
            $data = WorkOrder::select(\DB::raw('count(*) as total'))->whereDate('created_at', $date)->where('location_id', $currentlocation)->first();
            $arrTask['label'][] = $label;
            $arrTask['data'][] = $data->total;
        }

        return $arrTask;
    }

    public function getOrderChart($arrParam)
    {
        $arrDuration = [];
        if ($arrParam['duration']) {
            if ($arrParam['duration'] == 'week') {
                $previous_week = strtotime("-1 week +1 day");
                for ($i = 0; $i < 7; $i++) {
                    $arrDuration[date('Y-m-d', $previous_week)] = date('d-m', $previous_week);
                    $previous_week = strtotime(date('Y-m-d', $previous_week) . " +1 day");
                }
            }
        }
        $arrTask = [];
        $arrTask['label'] = [];
        $arrTask['data'] = [];
        foreach ($arrDuration as $date => $label) {
            $data = Order::select(\DB::raw('count(*) as total'))->whereDate('created_at', '=', $date)->first();
            $arrTask['label'][] = $label;
            $arrTask['data'][] = $data->total;
        }

  
        return $arrTask;
    }
}
