<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Modules\CMMS\Entities\Location;
use Modules\CMMS\Entities\Component;
use Modules\CMMS\Entities\Workorder;
use Modules\CMMS\Entities\Part;
use Modules\CMMS\Entities\Pms;
use Modules\CMMS\Entities\Supplier;
use Illuminate\Support\Facades\Crypt;
use Modules\CMMS\Events\CreateLocation;
use Modules\CMMS\Events\DestroyLocation;
use Modules\CMMS\Events\UpdateLocation;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if(Auth::user()->isAbleTo('location manage'))
        {
            $locations     = Location::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get();
            return view('cmms::location.index', compact('locations'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if(Auth::user()->isAbleTo('location create'))
        {
            return view('cmms::location.create');
        }
        else
        {
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
        if (Auth::user()->isAbleTo('location create'))
        {
            $objUser            = Auth::user();

            if (Auth::user()->user_type == 'company' || Auth::user()->user_type != 'company') {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'address' => 'required',
                ]);

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $count = Location::where('company_id', creatorId())->where('workspace', getActiveWorkSpace())->count();
                
                
                if($count<=0)
                {
                    
                    $current_location=1;
                }else
                {
                    $current_location=0;
                }
                
                $location = Location::create(
                    [
                        'created_by' => creatorId(),
                        'name' => $request->name,
                        'address' => $request->address,
                        'company_id' => creatorId(),
                        'workspace'   => getActiveWorkSpace(),
                        'current_location'=> $current_location,                        
                    ]
                );           
               
                event(new CreateLocation($request,$location));

                return redirect()->back()->with('success', __('Location Created Successfully!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        else 
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
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
    public function edit(Location $location)
    {
        if(Auth::user()->isAbleTo('location edit'))
        {
            if($location->created_by == creatorId() && $location->workspace == getActiveWorkSpace())
            {
               
                return view('cmms::location.edit',compact('location'));
            }
            else
            {
                 return response()->json(['error' => __('Permission Denied.')], 401);
            }
        }
        else
        {
             return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Location $location)
    {
        if (Auth::user()->isAbleTo('location edit')) {
            if($location->created_by == creatorId() && $location->workspace == getActiveWorkSpace())
            {
                $validator = Validator::make($request->all(), [
                    'name' => 'required',
                    'address' => 'required',
                ]);

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $location->name = $request->name;
                $location->address = $request->address;
                $location->save();

                event(new UpdateLocation($request,$location));

                return redirect()->back()->with('success', __('Location Updated Successfully!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
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
    public function destroy(Location $location)
    {

        $location_id = Location::where('company_id', '=',creatorId())->where('workspace','=' ,getActiveWorkSpace())->where('current_location' , 1)->first();
        if($location_id)
        {
            $locations = Location::where('company_id', '=',creatorId())->where('workspace','=' ,getActiveWorkSpace())->where('current_location' ,0)->first();
            if($locations)
            {
                $locations->current_location = 1;
                $locations->update();
            }            
        }
        

        $components = Component::where('company_id' , creatorId())->where('workspace',getActiveWorkSpace())->where('location_id' , $location->id)->get();
        foreach($components as $component)
        {
            $component->delete();
        }

        $workorders = Workorder::where('company_id' , creatorId())->where('workspace',getActiveWorkSpace())->where('location_id' , $location->id)->get();
        foreach($workorders as $workorder)
        {
            $workorder->delete();
        }

        $parts = Part::where('company_id' , creatorId())->where('workspace',getActiveWorkSpace())->where('location_id' , $location->id)->get();
            foreach($parts as $part)
            {
                $part->delete();
            }

        $pms = Pms::where('company_id' , creatorId())->where('workspace',getActiveWorkSpace())->where('location_id' , $location->id)->get();
        foreach($pms as $pms_val)
        {
            $pms_val->delete();
        }

        $suppliers = Supplier::where('company_id' , creatorId())->where('workspace',getActiveWorkSpace())->where('location_id' , $location->id)->get();
        foreach($suppliers as $supplier)
        {
            $supplier->delete();
        }

        if(Auth::user()->isAbleTo('location delete'))
        {
            if($location->created_by == creatorId() && $location->workspace == getActiveWorkSpace())
            {
                $location->delete();

                event(new DestroyLocation($location));

                return redirect()->route('location.index')->with('success', __('Location successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function changeCurrentLocation($locationID)
    {

        $locations = Location::where('company_id',creatorId())->where('workspace',getActiveWorkSpace())->get();
        foreach($locations as $location)
        {
            if($locationID == $location->id)
            {
                $location->current_location = 1;
                $location->save();
            }
            else
            {
                $location->current_location = 0;
                $location->save();
            }           
        }
            return redirect()->back()->with('success', __('Location Change Successfully!'));
    }

    public function work_request_portal($id , $lang)
    {
        
        $location_id = Crypt::decrypt($id);
        $location = Location::find($location_id);
        $components = Component::where(['company_id' => $location->company_id, 'workspace' => $location->workspace ,'location_id' => $location_id, 'is_active' => 1])->get()->pluck('name', 'id');
        return view('cmms::work_request.portal', compact('id', 'components' , 'lang'));
    }


}
