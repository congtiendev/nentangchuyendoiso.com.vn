<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMMS\Entities\Location;
use Illuminate\Support\Facades\Auth;
use Modules\CMMS\Entities\Component;
use Modules\CMMS\Entities\Form;
use Modules\CMMS\Entities\Workorder;
use Modules\CMMS\Entities\Pms;
use Modules\CMMS\Entities\Part;
use Modules\CMMS\Entities\PmsInvoice;
use Modules\CMMS\Entities\PmsLogTime;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use DB;
use Modules\CMMS\Events\CreatePms;
use Modules\CMMS\Events\DestroyPms;
use Modules\CMMS\Events\UpdatePms;

class PmsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('parts manage')) {
            $currentLocation = Location::userCurrentLocation();
            $locations = Location::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            $pms = Pms::with('getLocation')->where(['company_id' => creatorId(), 'workspace' => getActiveWorkSpace(), 'location_id' => $currentLocation, 'is_active' => 1])->get();

            return view('cmms::pms.index', compact('currentLocation', 'locations', 'pms'));
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
        if (\Auth::user()->isAbleTo('parts create')) {
            $components_id = !empty($request->components_id) ? $request->components_id : 0;
            $currentLocation = Location::userCurrentLocation();
            $locations = Location::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');;
            $parts = Part::where(['company_id' => creatorId(), 'workspace' => getActiveWorkSpace(), 'is_active' => 1, 'location_id' => $currentLocation])->get()->pluck('name', 'id');
            return view('cmms::pms.create', compact('locations', 'currentLocation', 'parts', 'components_id'));
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
    { {
            if (Auth::user()->isAbleTo('pms create')) {
                $objUser            = Auth::user();
                $currentlocation = Location::userCurrentLocation();
                if ($currentlocation == 0) {
                    return redirect()->back()->with('error', __('Current location is not available.'));
                }
                $valid = ['name' => 'required', 'parts' => 'required'];

                $validator = Validator::make($request->all(), $valid);
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }
                $pms = Pms::create([
                    'name'          => $request->name,
                    'location_id'   => $request->location,
                    'description'   => $request->description,
                    'parts_id'      => implode(',', $request->parts),
                    'tags'          => $request->tags,
                    'created_by'    => $objUser->id,
                    'company_id'    => creatorId(),
                    'workspace'     => getActiveWorkSpace(),
                    'is_active'     => 1
                ]);


                $form = Form::create([
                    'pms_id' => $pms->id,
                    'json' => "[]",
                    'created_by' => $objUser->id,
                    'company_id' => creatorId(),
                    'workspace'     => getActiveWorkSpace(),
                ]);

                event(new CreatePms($request, $pms));

                if ($pms) {
                    $components_id = $request->components_id;

                    if ($components_id != 0 && !empty($components_id)) {
                        $Components = Component::where(['id' => $components_id, 'company_id' => creatorId(), 'is_active' => 1])->first();
                        if (!is_null($Components)) {

                            $pms_id = [];
                            if (!empty($Components->pms_id)) {
                                $pms_id = explode(',', $Components->pms_id);
                            }
                            $pms_id[] = $pms->id;

                            $Components = Component::where('id', $components_id)->update(['pms_id' => implode(",", $pms_id)]);
                        }
                    }
                    return redirect()->back()->with(['success' => __('PMs created successfully.'), 'tab-status' => 'pms']);
                } else {
                    return redirect()->back()->with(['error' => __('Something went wrong.'), 'tab-status' => 'pms']);
                }

                return redirect()->back()->with(['success' => __('PMs created successfully.'), 'tab-status' => 'pms']);
            } else {
                return redirect()->back()->with(['error' => __('Permission Denied.'), 'tab-status' => 'pms']);
            }
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (Auth::user()->isAbleTo('pms show')) {
            $Pms = Pms::where(['id' => $id])->first();
            if (!is_null($Pms) && $Pms->is_active) {

                $module = "pms_part";


                $parts = DB::table('pms')->leftJoin('parts', function ($join) {
                    $join->whereRaw(DB::raw("find_in_set(parts.id,pms.parts_id)"));
                })->where('parts.id', '!=', null)->where('pms.id', $Pms->id)->get();

                $form = Form::where('pms_id', $id)->get();
                $pmslogtime = PmsLogTime::where('pms_id', $id)->get();
                $pmsinvoice = PmsInvoice::where('pms_id', $id)->get();

                $Pms_tag = explode(',', $Pms->tags);

                $instruction = Form::where('pms_id', $id)->first();
                $view_instruction = json_decode($instruction->json);

                return view('cmms::pms.view', compact('Pms', 'parts', 'module', 'Pms_tag', 'pmsinvoice', 'pmslogtime', 'form', 'view_instruction'));
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
        if (Auth::user()->isAbleTo('pms edit')) {
            $currentLocation = Location::userCurrentLocation();
            $location = Location::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $parts = Part::where(['company_id' => creatorId(), 'workspace' => getActiveWorkSpace(), 'is_active' => 1, 'location_id' => $currentLocation])->get()->pluck('name', 'id');

            $pms = Pms::where('workspace', getActiveWorkSpace())->where('id', $id)->get();
            $pms_role = explode(',', $pms[0]['parts_id']);
            return view('cmms::pms.edit', compact('currentLocation', 'location', 'pms', 'parts', 'pms_role'));
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
        if (Auth::user()->isAbleTo('pms edit')) {

            $objUser            = Auth::user();
            $currentlocation = Location::userCurrentLocation();
            if ($currentlocation == 0) {
                return redirect()->back()->with(['error' => __('Current location is not available.'), 'tab-status' => 'pms']);
            }
            $valid = ['name' => 'required'];

            $validator = Validator::make($request->all(), $valid);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            $pms['name']       = $request->name;
            $pms['description']       = $request->description;
            $pms['parts_id'] = implode(',', $request->parts);
            $pms['tags']       = $request->tags;
            $pms['location_id'] = $request->location;

            $pms = Pms::where('id', $id)->update($pms);

            event(new UpdatePms($request, $pms));

            return redirect()->back()->with(['success' => __('PMs updated successfully.'), 'tab-status' => 'pms']);
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
        if (Auth::user()->isAbleTo('pms delete')) {
            $pms = Pms::find($id);
            $Form = Form::where('pms_id', $id)->first();

            if ($pms) {
                $pms->delete();

                // event(new DestoryPms($pms));

                $Form->delete();
                return redirect()->route('pms.index')->with(['success' => __('PMs successfully deleted .'), 'tab-status' => 'pms']);
            } else {
                return redirect()->back()->with(['error' => __('Something is wrong.'), 'tab-status' => 'pms']);
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function getparts(Request $request)
    {
        if ($request->location_id == 0) {
            $parts = \Modules\CMMS\Entities\Part::get()->pluck('name', 'id')->toArray();
            return response()->json($parts);
        } else {
            $parts  = \Modules\CMMS\Entities\Part::where('location_id', $request->location_id)->get()->pluck('name', 'id');
            return response()->json($parts);
        }
    }
}
