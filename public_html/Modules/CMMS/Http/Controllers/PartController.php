<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMMS\Entities\Location;
use Illuminate\Support\Facades\Auth;
use Modules\CMMS\Entities\Component;
use Modules\CMMS\Entities\PartsLogTime;
use Modules\CMMS\Entities\Workorder;
use Modules\CMMS\Entities\Part;
use Modules\CMMS\Entities\Pms;
use Modules\CMMS\Entities\CmmsPosPart;
use Modules\CMMS\Entities\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use DB;
use Modules\CMMS\Events\CreatePart;
use Modules\CMMS\Events\DestroyPart;
use Modules\CMMS\Events\UpdatePart;

class PartController extends Controller
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
            $parts = Part::with('getLocation')->where(['company_id' => creatorId(), 'location_id' => $currentLocation, 'workspace' => getActiveWorkSpace(), 'is_active' => 1])->get();
            return view('cmms::parts.index', compact('currentLocation', 'locations', 'parts'));
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
            $components_id = $request->components_id;
            $pms_id = $request->pms_id;
            $supplier_id = $request->supplier_id;
            $workorder_id = $request->workorder_id;

            $currentLocation = Location::userCurrentLocation();
            $locations = Location::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');

            return view('cmms::parts.create', compact('locations', 'currentLocation', 'workorder_id', 'components_id', 'pms_id', 'supplier_id'));
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
            if (\Auth::user()->isAbleTo('parts create')) {
                $objUser            = Auth::user();
                $currentlocation = Location::userCurrentLocation();
                if ($currentlocation == 0) {
                    return redirect()->back()->with('error', __('Current location is not available.'));
                }

                $valid = ['name' => 'required', 'thumbnail' => 'required|image|mimes:png,jpeg,jpg|max:20480'];

                $validator = Validator::make($request->all(), $valid);
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                if ($request->thumbnail) {

                    $image_size = $request->file('thumbnail')->getSize();
                    $filenameWithExt = time() . '_' . $request->file('thumbnail')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('thumbnail')->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir        = 'Parts/thumbnail';
                    $url = '';
                    $path = upload_file($request, 'thumbnail', $filenameWithExt, $dir, []);
                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }
                }

                $parts = Part::create([
                    'name' => $request->name,
                    'thumbnail' => $url,
                    'number' => $request->number,
                    'quantity' => $request->quantity ?? 0,
                    'price' => $request->price ?? 0,
                    'category' => $request->category ?? 0,
                    'location_id' =>  $request->location,
                    'created_by' => $objUser->id,
                    'company_id' => creatorId(),
                    'workspace' => getActiveWorkSpace(),
                ]);

                event(new CreatePart($request, $parts));

                if ($parts) {

                    $components_id = $request->components_id;
                    $pms_id = $request->pms_id;
                    $supplier_id = $request->supplier_id;
                    $workorder_id = $request->workorder_id;
                    if ($components_id != 0 && !empty($components_id)) {

                        $Components = Component::where(['id' => $components_id, 'company_id' => creatorId(), 'is_active' => 1])->first();
                        if (!is_null($Components)) {
                            $parts_id = [];
                            if (!empty($Components->parts_id)) {
                                $parts_id = explode(',', $Components->parts_id);
                            }
                            $parts_id[] = $parts->id;

                            Component::where('id', $components_id)->update(['parts_id' => implode(',', $parts_id)]);
                        }
                    }
                    //pms detail page in parts create
                    elseif ($pms_id != 0 && !empty($pms_id)) {

                        $Pms = Pms::where(['id' => $pms_id, 'company_id' => creatorId(), 'is_active' => 1])->first();
                        if (!is_null($Pms)) {
                            $parts_id = [];
                            if (!empty($Pms->parts_id)) {
                                $parts_id = explode(',', $Pms->parts_id);
                            }
                            $parts_id[] = $parts->id;
                            Pms::where('id', $pms_id)->update(['parts_id' => implode(',', $parts_id)]);
                        }
                    } elseif ($supplier_id != 0 && !empty($supplier_id)) {

                        $Supplier = Supplier::where(['id' => $supplier_id, 'company_id' => creatorId(), 'is_active' => 1])->first();
                        if (!is_null($Supplier)) {
                            $parts_id = [];
                            if (!empty($Supplier->parts_id)) {
                                $parts_id = explode(',', $Supplier->parts_id);
                            }
                            $parts_id[] = $parts->id;
                            Supplier::where('id', $supplier_id)->update(['parts_id' => implode(',', $parts_id)]);
                        }
                    }
                    //work order deatil page in parts create
                    elseif ($workorder_id != 0 && !empty($workorder_id)) {

                        $WorkOrder = WorkOrder::where(['id' => $workorder_id, 'company_id' => creatorId(), 'is_active' => 1])->first();
                        if (!is_null($WorkOrder)) {
                            $parts_id = [];
                            if (!empty($WorkOrder->parts_id)) {
                                $parts_id = explode(',', $WorkOrder->parts_id);
                            }
                            $parts_id[] = $parts->id;

                            WorkOrder::where('id', $workorder_id)->update(['parts_id' => implode(',', $parts_id)]);
                        }
                    }
                    return redirect()->back()->with('success', __('Parts Created Successfully') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
                } else {
                    return redirect()->back()->with(['error' => __('Something went wrong.'), 'tab-status' => 'parts']);
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    { {

            if (\Auth::user()->isAbleTo('parts show')) {
                $Parts = Part::where(['id' => $id])->first();
                $module = "parts";

                $suppliers = DB::table('parts')->leftJoin('suppliers', function ($join) {
                    $join->whereRaw(DB::raw("find_in_set(suppliers.id,parts.supplier_id)"));
                })->where('suppliers.id', '!=', null)->where('parts.id', $Parts->id)->get();

                $componenets = DB::table('parts')->leftJoin('components', function ($join) {
                    $join->whereRaw(DB::raw("find_in_set(components.id,parts.components_id)"));
                })->where('components.id', '!=', null)->where('parts.id', $Parts->id)->get();

                $partslogtime = PartsLogTime::where('parts_id', $id)->get();

                $parts_pos = DB::table('cmms_pos')
                    ->join('users', 'cmms_pos.user_id', '=', 'users.id')
                    ->join('suppliers', 'cmms_pos.supplier_id', '=', 'suppliers.id')
                    ->select(DB::raw('cmms_pos.*, users.name as user_name, suppliers.name as supplier_name'))
                    ->where('cmms_pos.parts_id', $id)
                    ->get();

                $total_parts_purchase = CmmsPosPart::where('parts_id', $id)->count();
                $total_cost = CmmsPosPart::select(DB::raw('SUM(price*quantity+shipping+(price*quantity*tax/100)-discount) as total_cost'))->where('parts_id', $id)->first();


                return view('cmms::parts.view', compact('Parts', 'module', 'suppliers', 'componenets', 'partslogtime', 'parts_pos', 'total_parts_purchase', 'total_cost'));
            } else {
                return redirect()->back()->with('error', __("Permission Denied"));
            }
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

            $parts = Part::where('workspace', getActiveWorkSpace())->where('id', $id)->first();
            return view('cmms::parts.edit', compact('currentLocation', 'location', 'parts'));
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
    { {
            if (\Auth::user()->isAbleTo('parts edit')) {

                $objUser        = Auth::user();

                $currentlocation = Location::userCurrentLocation();
                if ($currentlocation == 0) {
                    return redirect()->back()->with('error', __('Current location is not available.'));
                }

                $thumbnail_name = '';
                if ($request->hasFile('thumbnail')) {

                    $part = Part::where('id', $id)->first();
                    $thumbnail_file = storage_path('Parts/thumbnail/' . $part->thumbnail);

                    $valid = ['name' => 'required', 'thumbnail' => 'required|image|mimes:png,jpeg,jpg|max:20480'];
                    $validator = Validator::make($request->all(), $valid);
                    if ($validator->fails()) {
                        $messages = $validator->getMessageBag();
                        return redirect()->back()->with('error', $messages->first());
                    }

                    $filenameWithExt = time() . '_' . $request->file('thumbnail')->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('thumbnail')->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                    $dir        = 'Parts/thumbnail';
                    $url = '';
                    $path = upload_file($request, 'thumbnail', $filenameWithExt, $dir, []);
                    if ($path['flag'] == 1) {
                        $url = $path['url'];
                    } else {
                        return redirect()->back()->with('error', __($path['msg']));
                    }


                    // $Company_ID = creatorId();
                    // $image_size = $request->file('thumbnail')->getSize();
                    // $file_path = 'Parts/thumbnail/' . $part->thumbnail;


                    // $thumbnail = $request->file('thumbnail');
                    // $thumbnail_name = time() . "_" . $thumbnail->getClientOriginalName();
                    // $thumbnail->storeAs('Parts/thumbnail', $thumbnail_name);
                    $parts['thumbnail']  = $url;
                }

                $parts['name']       = $request->name;
                $parts['number']     = $request->number;
                $parts['quantity']   = $request->quantity;
                $parts['price']      = $request->price;
                $parts['category']   = $request->category;
                $parts['location_id'] = $request->location;
                $parts['created_by'] = $objUser->id;
                $parts['company_id'] = creatorId();
                $parts['workspace']  = getActiveWorkSpace();


                $parts = Part::where('id', $id)->update($parts);

                event(new UpdatePart($request, $parts));

                if ($parts) {
                    return redirect()->back()->with('success', __('Parts Update Successfully') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
                } else {
                    return redirect()->back()->with('error', __('Something Went Wrong'));
                }
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (\Auth::user()->isAbleTo('parts delete')) {
            $parts = Part::find($id);
            if ($parts) {
                $parts->delete();

                event(new DestroyPart($parts));

                return redirect()->back()->with('success', __('Parts successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->error('error', __('Permission Denied'));
        }
    }

    public function associatePartsView($module, $id)
    {
        $objUser            = Auth::user();
        $currentLocation = Location::userCurrentLocation();

        //Asset detail page in parts
        if ($module == 'parts') {
            if (Auth::user()->isAbleTo('parts associate')) {
                if ($currentLocation == 0) {
                    return redirect()->back()->with('error', __('Current location is not available.'));
                }
                $component_parts = Component::find($id);
                $parts = Part::where(['company_id' => creatorId(), 'location_id' => $currentLocation, 'is_active' => 1])->whereNotIn('id', explode(',', $component_parts->parts_id))->get()->pluck('name', 'id');
                return view('cmms::parts.associate', compact('parts', 'id', 'module'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        }
        //Asset detail page in pms
        elseif ($module == 'pms') {
            if (Auth::user()->isAbleTo('pms associate')) {
                if ($currentLocation == 0) {
                    return redirect()->back()->with('error', __('Current location is not available.'));
                }
                $pms_parts = Component::find($id);
                $parts = Pms::where(['company_id' => creatorId(), 'location_id' => $currentLocation, 'workspace' => getActiveWorkSpace(), 'is_active' => 1])->whereNotIn('id', explode(',', $pms_parts->pms_id))->get()->pluck('name', 'id');
                return view('cmms::parts.associate', compact('parts', 'id', 'module'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        }
        //pms detail page in parts
        if ($module == 'pms_parts') {
            if (Auth::user()->isAbleTo('parts associate')) {
                if ($currentLocation == 0) {
                    return redirect()->back()->with('error', __('Current location is not available.'));
                }
                $pms_parts = Pms::find($id);
                $parts = Part::where(['company_id' => creatorId(), 'location_id' => $currentLocation, 'workspace' => getActiveWorkSpace(), 'is_active' => 1])->whereNotIn('id', explode(',', $pms_parts->parts_id))->get()->pluck('name', 'id');
                $pms_id = $id;
                return view('cmms::parts.associate', compact('parts', 'pms_id', 'id', 'module'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        }
        //vendors detail page in parts
        if ($module == 'suppliers') {
            if (Auth::user()->isAbleTo('parts associate')) {
                if ($currentLocation == 0) {
                    return redirect()->back()->with('error', __('Current location is not available.'));
                }
                $supplier_parts = Supplier::find($id);
                $parts = Part::where(['company_id' => creatorId(), 'location_id' => $currentLocation, 'workspace' => getActiveWorkSpace(), 'is_active' => 1])->whereNotIn('id', explode(',', $supplier_parts->parts_id))->get()->pluck('name', 'id');
                $data_id = $id;
                return view('cmms::parts.associate', compact('parts', 'data_id', 'id', 'module'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        }
        //work order deatil page in parts
        if ($module == 'workorder') {
            if (Auth::user()->isAbleTo('parts associate')) {
                if ($currentLocation == 0) {
                    return redirect()->back()->with('error', __('Current location is not available.'));
                }
                $wo_parts = Workorder::find($id);
                $parts = Part::where(['company_id' => creatorId(), 'location_id' => $currentLocation, 'workspace' => getActiveWorkSpace(), 'is_active' => 1])->whereNotIn('id', explode(',', $wo_parts->parts_id))->get()->pluck('name', 'id');

                $data_id = $id;
                return view('cmms::parts.associate', compact('parts', 'data_id', 'id', 'module'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        }
    }

    public function associateParts(Request $request, $module, $id)
    {
        $valid = ['associate_parts' => 'required'];

        $validator = Validator::make($request->all(), $valid);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $objUser            = Auth::user();
        $currentlocation = Location::userCurrentLocation();

        //Asset Detail page in parts
        if ($module == 'parts') {

            $components = Component::where(['id' => $id, 'company_id' => creatorId(), 'is_active' => 1])->first();
            if (!is_null($components)) {

                $components_part_id = empty($components->parts_id) ? implode(',', $request->associate_parts) : $components->parts_id . ',' . implode(',', $request->associate_parts);
                $components->parts_id = $components_part_id;
                $components->save();

                return redirect()->back()->with(['success' => __('Parts associate to component successfully.'), 'tab-status' => 'parts']);
            } else {

                return redirect()->back()->with(['error' => __('Parts is not available.'), 'tab-status' => 'parts']);
            }
        }
        //Asset Detail page in pms
        elseif ($module == 'pms') {

            $Pms = Component::where(['id' => $id, 'company_id' => creatorId(), 'is_active' => 1])->first();
            if (!is_null($Pms)) {

                $pms_part_id = empty($Pms->pms_id) ? implode(',', $request->associate_parts) : $Pms->pms_id . ',' . implode(',', $request->associate_parts);
                $Pms->pms_id = $pms_part_id;
                $Pms->save();

                return redirect()->back()->with(['success' => __('Pms associate to component successfully.'), 'tab-status' => 'pms']);
            } else {

                return redirect()->back()->with(['error' => __('Pms is not available.'), 'tab-status' => 'pms']);
            }
        }
        //pms detail page in part
        elseif ($module == 'pms_parts') {
            $pms = Pms::where(['id' => $id, 'company_id' => creatorId(), 'is_active' => 1])->first();

            if (!is_null($pms)) {
                $parts_id = empty($pms->parts_id) ? implode(',', $request->associate_parts) : $pms->parts_id . ',' . implode(',', $request->associate_parts);
                $pms->parts_id = $parts_id;
                $pms->save();
                return redirect()->back()->with(['success' => __('Parts associate to pms successfully.'), 'tab-status' => 'parts']);
            } else {
                return redirect()->back()->with(['error' => 'Parts is not available.', 'tab-status' => 'parts']);
            }
        }
        //vendors deatil page in part
        elseif ($module == 'suppliers') {

            $Supplier = Supplier::where(['id' => $id, 'company_id' => creatorId(), 'is_active' => 1])->first();
            if (!is_null($Supplier)) {

                $supplier_part_id = empty($Supplier->parts_id) ? implode(',', $request->associate_parts) :  $Supplier->parts_id . ',' . implode(',', $request->associate_parts);
                $Supplier->parts_id = $supplier_part_id;
                $Supplier->save();

                return redirect()->back()->with(['success' => __('Parts associate to supplier successfully.'), 'tab-status' => 'parts']);
            } else {

                return redirect()->back()->with(['error' => __('Parts is not available.'), 'tab-status' => 'parts']);
            }
        }
        //workorder detail page in part
        elseif ($module == 'workorder') {

            $Workorder = WorkOrder::where(['id' => $id, 'company_id' => creatorId(), 'is_active' => 1])->first();

            if (!is_null($Workorder)) {

                $wo_part_id = empty($Workorder->parts_id) ?  implode(',', $request->associate_parts) : $Workorder->parts_id . ',' . implode(',', $request->associate_parts);
                $Workorder->parts_id = $wo_part_id;
                $Workorder->save();


                return redirect()->back()->with(['success' => __('Parts associate to Open Task successfully.'), 'tab-status' => 'parts']);
            } else {

                return redirect()->back()->with(['error' => __('Component is not available.'), 'tab-status' => 'parts']);
            }
        } else {
            return redirect()->back()->with(['error' => __('Something went to wrong.'), 'tab-status' => 'parts']);
        }
    }


    public function removeAssociateParts(Request $request, $module, $id)
    {
        //Asset Detail page in parts
        if ($module == "components") {
            $Component = Component::where(['id' => $request->components_id, 'is_active' => 1])->first();
            $component_part_id = explode(',', $Component->parts_id);
            unset($component_part_id[array_search($id, $component_part_id)]);
            $component_part_id = array_filter($component_part_id);
            $Component->parts_id = implode(',', $component_part_id);
            $Component->save();
            return redirect()->back()->with(['success' => __('Parts successfully deleted.'), 'tab-status' => 'parts']);
        }
        //Asset Detail Page in pms
        elseif ($module == "pms") {

            $Components = Component::where(['id' => $request->components_id, 'is_active' => 1])->first();
            $pms_part_id = explode(',', $Components->pms_id);
            unset($pms_part_id[array_search($id, $pms_part_id)]);
            $pms_part_id = array_filter($pms_part_id);
            $Components->pms_id = implode(',', $pms_part_id);
            $Components->save();
            return redirect()->back()->with(['success' => __('Pms successfully deleted.'), 'tab-status' => 'pms']);
        }
        //pms detail page in parts
        elseif ($module == 'pms_part') {
            $Pms = Pms::where(['id' => $request->pms_id, 'is_active' => 1])->first();
            $pms_part_id = explode(",", $Pms->parts_id);
            unset($pms_part_id[array_search($id, $pms_part_id)]);
            $pms_part_id = array_filter($pms_part_id);
            $Pms->parts_id = implode(",", $pms_part_id);
            $Pms->save();
            return redirect()->back()->with(['success' => __('Parts successfullt deleted.'), 'tab-status' => 'parts']);
        }
        //vendros detail page in parts
        elseif ($module == "suppliers") {

            $Supplier = Supplier::where(['id' => $request->supplier_id, 'is_active' => 1])->first();
            $supplier_part_id = explode(',', $Supplier->parts_id);
            unset($supplier_part_id[array_search($id, $supplier_part_id)]);
            $supplier_part_id = array_filter($supplier_part_id);
            $Supplier->parts_id = implode(',', $supplier_part_id);
            $Supplier->save();
            return redirect()->back()->with(['success' => __('Part successfully deleted.'), 'tab-status' => 'parts']);
        }
        //work order detail page in parts
        elseif ($module == "workorder") {

            $Workorder = WorkOrder::where(['id' => $request->workorder_id, 'is_active' => 1])->first();
            $wo_part_id = explode(',', $Workorder->parts_id);
            unset($wo_part_id[array_search($id, $wo_part_id)]);
            $wo_part_id = array_filter($wo_part_id);
            $Workorder->parts_id = implode(',', $wo_part_id);
            $Workorder->save();
            return redirect()->back()->with(['success' => __('Part successfully deleted.'), 'tab-status' => 'parts']);
        }
    }
}
