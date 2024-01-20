<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\CMMS\Entities\Location;
use Modules\CMMS\Entities\PmsLogTime;
use Illuminate\Support\Facades\Validator;

class PmsLogTimeController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('cmms::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        $pms_id = $request->pms_id;
        return view('cmms::pms.pmslogtime_create', compact('pms_id'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
            $objUser            = Auth::user();
            $currentlocation = Location::userCurrentLocation();
            if($currentlocation == 0){
                return redirect()->back()->with(['error', __('Current location is not available.'),'tab-status' => 'log_time']);
            }
            $valid = [
                'hours' => 'required',
                'minute' => 'required',
                'date' => 'required',
                      ];

            $validator = Validator::make($request->all(), $valid);
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $pmslogtime = PmsLogTime::create([
                'pms_id' => $request->pms_id,
                'hours' => $request->hours,
                'minute' => $request->minute,
                'date' => $request->date,
                'description' => $request->description,
                'location_id' => $currentlocation,
                'created_by' => $objUser->id,
                'company_id' => creatorId(),
                'workspace'  => getActiveWorkSpace()
            ]);

            if($pmslogtime){
                return redirect()->back()->with(['success'=> __('Pms Log Time created successfully.'),'tab-status' => 'log_time']);
            }else{
                return redirect()->back()->with(['error'=> __('Something went wrong.'),'tab-status' => 'log_time']);
            }

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
        $pmslogtime = PmsLogTime::find($id);
        return view('cmms::pms.pmslogtime_edit', compact('pmslogtime'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $objUser            = Auth::user();
        $currentlocation = Location::userCurrentLocation();
        if($currentlocation == 0){
            return redirect()->back()->with(['error'=> __('Current location is not available.'),'tab-status' => 'log_time']);
        }

        $pmslogtime['pms_id']      = $request->pms_id;
        $pmslogtime['hours']       = $request->hours;
        $pmslogtime['minute']      = $request->minute;
        $pmslogtime['date']        = $request->date;
        $pmslogtime['description'] = $request->description;

        $pmslogtime = PmsLogTime::where('id',$id)->update($pmslogtime);

        return redirect()->back()->with(['success'=> __('Pms Log Time update successfully.'),'tab-status' => 'log_time']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $pmslogtime = PmsLogTime::find($id);

        if($pmslogtime)
        {
                $pmslogtime->delete();
            return redirect()->back()->with(['success'=> __('Pms Log Time successfully deleted .'),'tab-status' => 'log_time']);
        }
        else
        {
            return redirect()->back()->with(['error'=> __('Something is wrong.'),'tab-status' => 'log_time']);
        }
    }
}
