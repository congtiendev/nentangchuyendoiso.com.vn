<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMMS\Entities\Location;
use Illuminate\Support\Facades\Auth;
use Modules\CMMS\Entities\Component;
use Modules\CMMS\Entities\Workorder;
use Modules\CMMS\Entities\Supplier;
use Modules\CMMS\Entities\Part;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Models\EmailTemplate;
use DB;
use Modules\CMMS\Events\CreateSupplier;
use Modules\CMMS\Events\DestroySupplier;
use Modules\CMMS\Events\UpdateSupplier;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('suppliers manage')) {
            $currentLocation = Location::userCurrentLocation();
            $locations = Location::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            $suppliers = Supplier::with('getLocation')->where(['company_id' => creatorId(), 'location_id' => $currentLocation, 'workspace' => getActiveWorkSpace(), 'is_active' => 1])->get();
            return view('cmms::supplier.index', compact('currentLocation', 'locations', 'suppliers'));
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
        if (\Auth::user()->isAbleTo('suppliers create')) {
            $parts_id = $request->parts_id;
            $components_id = $request->components_id;
            $currentLocation = Location::userCurrentLocation();
            $locations = Location::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            return view('cmms::supplier.create', compact('currentLocation', 'locations', 'components_id', 'parts_id'));
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
        if (\Auth::user()->isAbleTo('suppliers create')) {

            $objUser            = Auth::user();
            $currentlocation = Location::userCurrentLocation();

            if ($currentlocation == 0) {
                return redirect()->back()->with('error', __('Current location is not available.'));
            }

            $valid = [
                'name' => 'required',
                'email' => 'required|email',
            ];

            $validator = Validator::make($request->all(), $valid);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                $response = [
                    'is_success' => $is_success,
                    'message' => $messages->first()
                ];
                return redirect()->back()->with('error', $messages->first());
            }

            $image_name = '';

            if ($request->hasFile('image')) {
                $image_size = $request->file('image')->getSize();
                $filenameWithExt = time() . '_' . $request->file('image')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('image')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir        = 'Supplier';
                $url = '';
                $path = upload_file($request, 'image', $fileNameToStore, $dir, []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }


            $suppliers = Supplier::create([
                'name' => $request->name,
                'contact' => $request->contact,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'image' => !empty($url) ? $url : ' ',
                'location_id' => $request->location,
                'created_by' => $objUser->id,
                'company_id' => creatorId(),
                'workspace'  => getActiveWorkSpace()
            ]);

            event(new CreateSupplier($request, $suppliers));

            if ($suppliers) {
                $parts_id = $request->parts_id;
                $components_id = $request->components_id;

                //parts detail page in associate vendor in craete vendor
                if ($parts_id != 0 && !empty($parts_id)) {

                    $Parts = Part::where(['id' => $parts_id, 'company_id' => creatorId(), 'is_active' => 1])->first();

                    if (!is_null($Parts)) {

                        $supplier_id = [];
                        if (!empty($Parts->supplier_id)) {
                            $supplier_id = explode(',', $Parts->supplier_id);
                        }
                        $supplier_id[] = $suppliers->id;

                        Part::where('id', $parts_id)->update(['supplier_id' => implode(',', $supplier_id)]);
                    }
                } elseif ($components_id != 0 && !empty($components_id)) {

                    $Components = Component::where(['id' => $components_id, 'company_id' => creatorId(), 'is_active' => 1])->first();
                    if (!is_null($Components)) {
                        $supplier_id = [];
                        if (!empty($Components->supplier_id)) {
                            $supplier_id = explode(',', $Components->supplier_id);
                        }
                        $supplier_id[] = $suppliers->id;

                        Component::where('id', $components_id)->update(['supplier_id' => implode(',', $supplier_id)]);
                    }
                }

                if (!empty(company_setting('New Supplier')) && company_setting('New Supplier')  == true) {

                    $uArr = [
                        'name' => $request->name,
                        'email' => $request->email,
                        'contact' => $request->contact,
                    ];
                    try {
                        $resp = EmailTemplate::sendEmailTemplate('New Supplier', [$request->email], $uArr);
                    } catch (\Exception $e) {
                        $resp['error'] = $e->getMessage();
                    }

                    return redirect()->back()->with('success', __('Supplier  successfully created.') . ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                }

                return redirect()->back()->with(['success' => __('Supplier created successfully.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''), 'tab-status' => 'supplier']);
            } else {
                return redirect()->back()->with(['error' => __('Something went wrong.'), 'tab-status' => 'supplier']);
            }
        } else {
            return redirect()->back()->with('error ', __('Permission Denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        if (\Auth::user()->isAbleTo('suppliers show')) {
            $Supplier = Supplier::where(['id' => $id])->first();
            $module = "suppliers";

            $parts = DB::table('suppliers')->leftJoin('parts', function ($join) {
                $join->whereRaw(DB::raw("find_in_set(parts.id,suppliers.parts_id)"));
            })->where('parts.id', '!=', null)->where('suppliers.id', $Supplier->id)->get();

            $components = DB::table('suppliers')->leftJoin('components', function ($join) {
                $join->whereRaw(DB::raw("find_in_set(components.id,suppliers.components_id)"));
            })->where('components.id', '!=', null)->where('suppliers.id', $Supplier->id)->get();

            $supplier_pos = DB::table('cmms_pos')
                ->join('users', 'cmms_pos.user_id', '=', 'users.id')
                ->join('suppliers', 'cmms_pos.supplier_id', '=', 'suppliers.id')
                ->select(DB::raw('cmms_pos.*, users.name as user_name, suppliers.name as supplier_name'))
                ->where('cmms_pos.supplier_id', $id)
                ->get();

            return view('cmms::supplier.view', compact('Supplier', 'parts', 'module', 'components', 'supplier_pos'));
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        if (\Auth::user()->isAbleTo('suppliers edit')) {
            $currentLocation = Location::userCurrentLocation();
            $location = Location::where('created_by', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $suppliers = Supplier::find($id);

            return view('cmms::supplier.edit', compact('suppliers', 'location', 'currentLocation'));
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
        if (\Auth::user()->isAbleTo('suppliers edit')) {
            $objUser            = Auth::user();
            $currentlocation = Location::userCurrentLocation();

            if ($currentlocation == 0) {
                return redirect()->back()->with('error', __('Current location is not available.'));
            }
            $valid = ['name' => 'required'];

            $validator = Validator::make($request->all(), $valid);
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }


            if ($request->hasFile('image')) {

                $supplier = Supplier::where('id', $id)->first();
                $thumbnail_file = storage_path('Supplier/' . $supplier->thumbnail);

                $valid = ['name' => 'required', 'image' => 'required|image|mimes:png,jpeg,jpg|max:20480'];
                $validator = Validator::make($request->all(), $valid);
                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();
                    return redirect()->back()->with('error', $messages->first());
                }

                $image_size = $request->file('image')->getSize();
                $filenameWithExt = time() . '_' . $request->file('image')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('image')->getClientOriginalExtension();
                $dir        = 'Supplier';
                $url = '';
                $path = upload_file($request, 'image', $filenameWithExt, $dir, []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
                $suppliers['image']  = $url;
            }

            $suppliers['name']       = $request->name;
            $suppliers['contact']    = $request->contact;
            $suppliers['email']      = $request->email;
            $suppliers['phone']      = $request->phone;
            $suppliers['address']    = $request->address;
            $suppliers['location_id'] = $request->location;
            $suppliers['created_by'] = $objUser->id;
            $suppliers['company_id'] = creatorId();
            $suppliers['workspace']  = getActiveWorkSpace();

            $suppliers = Supplier::where('id', $id)->update($suppliers);

            event(new UpdateSupplier($request, $suppliers));

            if ($suppliers) {
                return redirect()->back()->with('success', __('Supplier update successfully.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
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
        if (\Auth::user()->isAbleTo('suppliers delete')) {
            $suppliers = Supplier::find($id);

            if ($suppliers) {
                $suppliers->delete();

                event(new DestroySupplier($suppliers));

                return redirect()->back()->with('success', __('Supplier successfully deleted .'));
            } else {
                return redirect()->back()->with('error', __('Something is wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Deined.'));
        }
    }

    public function associateSuppliersView($module, $id)
    {

        $objUser            = Auth::user();
        $currentlocation = Location::userCurrentLocation();

        //Parts detail page in vendor
        if ($module == 'parts_supplier') {

            if (Auth::user()->isAbleTo('suppliers associate')) {

                if ($currentlocation == 0) {
                    return redirect()->back()->with('error', __('Current location is not available.'));
                }
                $parts = Part::find($id);

                $supplier = Supplier::where(['company_id' => creatorId(), 'location_id' => $currentlocation, 'is_active' => 1])->whereNotIn('id', explode(',', $parts->supplier_id))->get()->pluck('name', 'id');

                $parts_id = $id;

                return view('cmms::supplier.associate', compact('supplier', 'parts_id', 'id', 'module'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        //Asset detail page in associate vendor
        if ($module == 'component_supplier') {

            if (Auth::user()->isAbleTo('suppliers associate')) {
                if ($currentlocation == 0) {
                    return redirect()->back()->with('error', __('Current location is not available.'));
                }

                $Component = Component::find($id);
                $supplier = Supplier::where(['company_id' => creatorId(), 'location_id' => $currentlocation, 'is_active' => 1])->whereNotIn('id', explode(',', $Component->supplier_id))->get()->pluck('name', 'id');
                $parts_id = $id;

                return view('cmms::supplier.associate', compact('supplier', 'parts_id', 'id', 'module'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
    }

    public function removeAssociateSuppliers(Request $request, $module, $id)
    {
        //Parts detail page in associated vendor remove
        if ($module == "parts_supplier") {
            $Parts = Part::where(['id' => $request->supplier_id, 'is_active' => 1])->first();
            $parts_supplier_id = explode(',', $Parts->supplier_id);
            unset($parts_supplier_id[array_search($id, $parts_supplier_id)]);
            $parts_supplier_id = array_filter($parts_supplier_id);
            $Parts->supplier_id = implode(',', $parts_supplier_id);
            $Parts->save();
            return redirect()->back()->with(['success' => __('Supplier successfully deleted.'), 'tab-status' => 'supplier']);
        }
        //Asset detail page in associated vendor remove
        elseif ($module == "component_supplier") {
            $Component = Component::where(['id' => $request->components_id, 'is_active' => 1])->first();
            $component_supplier_id = explode(',', $Component->supplier_id);
            unset($component_supplier_id[array_search($id, $component_supplier_id)]);

            $component_supplier_id = array_filter($component_supplier_id);

            $Component->supplier_id = implode(',', $component_supplier_id);

            $Component->save();

            return redirect()->back()->with(['success' => __('Supplier successfully deleted.'), 'tab-status' => 'supplier']);
        } else {
            return redirect()->back()->with(['error' => __('Something went to wrong.'), 'tab-status' => 'supplier']);
        }
    }

    public function associateSuppliers(Request $request, $module, $id)
    {
        $objUser            = Auth::user();
        $currentlocation = Location::userCurrentLocation();

        //Parts detail page in associate vendor
        if ($module == 'parts_supplier') {

            $Parts = Part::where(['id' => $id, 'company_id' => creatorId(), 'is_active' => 1])->first();
            if (!is_null($Parts)) {

                $parts_supplier_id = empty($Parts->supplier_id) ? implode(',', $request->associate_supplier) : $Parts->supplier_id . ',' . implode(',', $request->associate_supplier);

                $Parts->supplier_id = $parts_supplier_id;
                $Parts->save();

                return redirect()->back()->with(['success' => __('Supplier associate to parts successfully.'), 'tab-status' => 'supplier']);
            } else {

                return redirect()->back()->with(['error' => __('Supplier is not available.'), 'tab-status' => 'supplier']);
            }
        }
        //Asset detail page in Associate vendor
        elseif ($module == 'component_supplier') {

            $Component = Component::where(['id' => $id, 'company_id' => creatorId(), 'is_active' => 1])->first();
            if (!is_null($Component)) {

                $components_supplier_id =  empty($Component->supplier_id) ?  implode(',', $request->associate_supplier) : $Component->supplier_id . ',' . implode(',', $request->associate_supplier);
                $Component->supplier_id = $components_supplier_id;
                $Component->save();

                return redirect()->back()->with(['success' => __('Supplier associate to Component ssuccessfully.'), 'tab-status' => 'supplier']);
            } else {

                return redirect()->back()->with(['error' => __('Supplier is not available.'), 'tab-status' => 'supplier']);
            }
        } else {
            return redirect()->back()->with('error', __('Something went to wrong.'));
        }
    }
}
