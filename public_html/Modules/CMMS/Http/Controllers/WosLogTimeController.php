<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\CMMS\Entities\ComponentsField;
use Modules\CMMS\Entities\Component;
use Modules\CMMS\Entities\WosLogTime;
use Illuminate\Support\Facades\Validator;
use Modules\CMMS\Entities\Location;
use App\Models\User;
use DB;

class WosLogTimeController extends Controller
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
        if (Auth::user()->isAbleTo('logtime create')) {
            $objUser            = Auth::user();
            $currentlocation = Location::userCurrentLocation();
            $wo_id = $request->wo_id;
            $users = User::where('created_by',creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('cmms::workorder.wologtime_create', compact('wo_id', 'users'));
        } else {
            return redirect()->back()->with('error', 'Permission Denied.');
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
        if (Auth::user()->isAbleTo('logtime create')) {

            $objUser            = Auth::user();
            $currentlocation = Location::userCurrentLocation();
            if ($currentlocation == 0) {
                return redirect()->back()->with('error', __('Current location is not available.'));
            }

            $valid = [
                'hours' => 'required',
                'minute' => 'required',
                'date' => 'required',
            ];

            $validator = Validator::make($request->all(), $valid);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            if ($objUser->user_type == "company") {
                $user_id = $request->user_id;
            } else {
                $user_id = $objUser->id;
            }

            $woslogtime = WosLogTime::create([
                'wo_id'       => $request->wo_id,
                'user_id'     => $user_id,
                'hours'       => $request->hours,
                'minute'      => $request->minute,
                'date'        => $request->date,
                'description' => $request->description,
                'location_id' => $currentlocation,
                'created_by'  => $objUser->id,
                'company_id'  => creatorId(),
                'workspace'   => getActiveWorkSpace(),
            ]);

            if ($woslogtime) {
                return redirect()->back()->with(['success' => __('Log time created successfully.'), 'tab-status' => 'log_time']);
            } else {
                return redirect()->back()->with(['error' => __('Something went wrong.'), 'tab-status' => 'log_time']);
            }
        } else {
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

    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if(Auth::user()->isAbleTo('logtime edit'))
        {
            $objUser            = Auth::user();
            $currentlocation = Location::userCurrentLocation();
            $woslogtime = WosLogTime::find($id);
    
            $users = User::where('created_by',creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('cmms::workorder.wologtime_edit', compact('woslogtime', 'users'));
        }
        else
        {
            return redirect()->back()->with('error',__('Permission Denied'));
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
        if(Auth::user()->isAbleTo('logtime edit'))
        {
            $objUser            = Auth::user();
            $currentlocation = Location::userCurrentLocation();
            if ($currentlocation == 0) {
                return redirect()->back()->with(['error' => __('Current location is not available.'), 'tab-status' => 'log_time']);
            }

            if ($objUser->user_type == "company") {
                $user_id = $request->user_id;
            } else {
                $user_id = $objUser->id;
            }

            $woslogtime['wo_id']       = $request->wo_id;
            $woslogtime['hours']       = $request->hours;
            $woslogtime['minute']      = $request->minute;
            $woslogtime['date']        = $request->date;
            $woslogtime['description'] = $request->description;
            $woslogtime['user_id']     = $user_id;

            $woslogtime = WosLogTime::where('id', $id)->update($woslogtime);


            return redirect()->back()->with(['success' => __('Log Time update successfully.'), 'tab-status' => 'log_time']);        
        }
        else
        {
            return redirect()->back()->with('error' , __('Permission Denied'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        {
            if (Auth::user()->isAbleTo('logtime delete')) {
                $woslogtime = WosLogTime::find($id);
    
                if ($woslogtime) {

                        $woslogtime->delete();
                    return redirect()->back()->with('success', __(' Log Time deleted successfully .'));
                } else {
                    return redirect()->back()->with('error', __('Something is wrong.'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
    }
}
