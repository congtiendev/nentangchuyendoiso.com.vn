<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMMS\Entities\PartsLogTime;
use Illuminate\Support\Facades\Auth;
use Modules\CMMS\Entities\Location;
use Illuminate\Support\Facades\Validator;

class PartsLogTimeController extends Controller
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
        $parts_id = $request->parts_id;
        return view('cmms::parts.partslogtime_create', compact('parts_id'));
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
        if ($currentlocation == 0) {
            return redirect()->back()->with('error', __('Current location is not available.'));
        }
        $valid = [
            'date' => 'required',
        ];

        $validator = Validator::make($request->all(), $valid);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $partslogtime = PartsLogTime::create([
            'parts_id' => $request->parts_id,
            'hours' => $request->hours,
            'minute' => $request->minute,
            'date' => $request->date,
            'description' => $request->description,
            'location_id' => $currentlocation,
            'created_by' => $objUser->id,
            'company_id' => creatorId(),
            'workspace' => getActiveWorkSpace(),
        ]);

        if ($partslogtime) {
            return redirect()->back()->with(['success' => __('Pms created successfully.'), 'tab-status' => 'log_time']);
        } else {
            return redirect()->back()->with(['error' => __('Something went wrong.'), 'tab-status' => 'log_time']);
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
        $partslogtime = PartsLogTime::find($id);
        return view('cmms::parts.partslogtime_edit', compact('partslogtime'));
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
        if ($currentlocation == 0) {
            return redirect()->back()->with('error', __('Current location is not available.'));
        }

        $partslogtime['parts_id']        = $request->parts_id;
        $partslogtime['hours']           = $request->hours;
        $partslogtime['minute']     = $request->minute;
        $partslogtime['date']       = $request->date;
        $partslogtime['description']       = $request->description;

        $partslogtime = PartsLogTime::where('id', $id)->update($partslogtime);

        return redirect()->back()->with(['success' => __('Parts Log Time update successfully.'), 'tab-status' => 'log_time']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $partslogtime = PartsLogTime::find($id);

        if ($partslogtime) {
            $partslogtime->delete();
            return redirect()->back()->with(['success' => __('Parts Log Time successfully deleted .'), 'tab-status' => 'log_time']);
        } else {
            return redirect()->back()->with(['error' => __('Something is wrong.'), 'tab-status' => 'log_time']);
        }
    }
}
