<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMMS\Entities\Location;
use Modules\CMMS\Entities\ComponentsLogTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ComponentsLogTimeController extends Controller
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
        $components_id = $request->components_id; 
        return view('cmms::component.componentslogtime_create', compact('components_id'));
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
                return redirect()->back()->with(['error'=> __('Current location is not available.'),'tab-status' => 'log_time']);
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

            $componentsLogTime = ComponentsLogTime::create([
                'components_id' => $request->components_id,
                'hours' => $request->hours,
                'minute' => $request->minute,
                'date' => $request->date,
                'description' => $request->description,
                'location_id' => $currentlocation,
                'created_by' => $objUser->id,
                'company_id' => creatorId(),
                'workspace' => getActiveWorkSpace()
            ]);

            if($componentsLogTime){
                return redirect()->back()->with(['success'=> __('Components Log Time created successfully.'),'tab-status' => 'log_time']);
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
        $componentsLogTime = ComponentsLogTime::find($id);
        return view('cmms::component.componentslogtime_edit', compact('componentsLogTime'));
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

        $componentsLogTime['components_id']     = $request->components_id;
        $componentsLogTime['hours']      =  $request->hours;
        $componentsLogTime['minute']     = $request->minute;
        $componentsLogTime['date']       = $request->date;
        $componentsLogTime['description']       = $request->description;

        $componentsLogTime = ComponentsLogTime::where('id',$id)->update($componentsLogTime);

        return redirect()->back()->with(['success'=> __('Components Log Time update successfully.'),'tab-status' => 'log_time']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $componentsLogTime = ComponentsLogTime::find($id);

        if($componentsLogTime)
        {
            $componentsLogTime->delete();
            return redirect()->back()->with(['success' => __('Components Log Time successfully deleted .'),'tab-status' => 'log_time']);
        }
        else
        {
            return redirect()->back()->with(['error' => __('Something is wrong.'),'tab-status' => 'log_time']);
        }
    }
}
