<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\CMMS\Entities\ComponentsField;
use Modules\CMMS\Entities\Component;
use Modules\CMMS\Entities\ComponentsFieldValues;
use Modules\CMMS\Entities\Location;
use Modules\CMMS\Entities\Workorder;
use Modules\CMMS\Entities\ComponentsLogTime;
use Modules\CMMS\Entities\Part;
use Modules\CMMS\Entities\Supplier;
use DB;
use Modules\CMMS\Events\CreateComponent;
use Modules\CMMS\Events\DestroyComponent;
use Modules\CMMS\Events\UpdateComponent;

class ComponentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

        if (Auth::user()->isAbleTo('components manage')) {
            $currentLocation = Location::userCurrentLocation();
            $locations = Location::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            $components     = Component::with('getLocation')->where('company_id', creatorId())->where('workspace', getActiveWorkSpace())->where('location_id', $currentLocation)->get();
            return view('cmms::component.index', compact('components', 'locations', 'currentLocation'));
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
        if (Auth::user()->isAbleTo('components create')) {
            $currentLocation = Location::userCurrentLocation();
            $ComponentsField = ComponentsField::where(['module' => 'Components'])->get();
            $locations = Location::where('workspace', getActiveWorkSpace())->where('created_by', creatorId())->get()->pluck('name', 'id');
            $parts_id = $request->parts_id;
            $supplier_id = $request->supplier_id;

            return view('cmms::component.create', compact('ComponentsField', 'locations', 'currentLocation', 'parts_id', 'supplier_id'));
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
        if (\Auth::user()->isAbleTo('components create')) {

            $objUser            = Auth::user();

            if ($request->thumbnail) {

                $image_size = $request->file('thumbnail')->getSize();

                $valid = ['name' => 'required', 'sku' => 'requied', 'thumbnail' => 'required'];
                $filenameWithExt = time() . '_' . $request->file('thumbnail')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('thumbnail')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;



                $dir        = 'Components/thumbnail/';
                $url = '';
                $path = upload_file($request, 'thumbnail', $filenameWithExt, $dir, []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
            }

            $thumbnail_name = '';

            $components = Component::create([
                'name' => $request->name,
                'sku' => $request->sku,
                'thumbnail' => !empty($url) ? $url : '',
                'location_id' => $request->location,
                'created_by' => $objUser->id,
                'company_id' => creatorId(),
                'workspace' => getActiveWorkSpace()
            ]);

            event(new CreateComponent($request, $components));

            if ($components) {
                $parts_id = $request->parts_id;
                $supplier_id = $request->supplier_id;

                //parts detail page in asset created that time asset table in entry insert and parts in asset_id update
                if ($parts_id != 0 && !empty($parts_id)) {
                    $Parts = Part::where(['id' => $parts_id, 'company_id' => creatorId(), 'is_active' => 1])->first();
                    if (!is_null($Parts)) {
                        $components_id = [];
                        if (!empty($Parts->components_id)) {
                            $components_id = explode(',', $Parts->components_id);
                        }
                        $components_id[] = $components->id;
                        $Parts->update(['components_id' => implode(',', $components_id)]);
                    }
                }

                //vendors detail page in asset in associated assets in create assets
                if ($supplier_id != 0 && !empty($supplier_id)) {
                    $suppliers = Supplier::where(['id' => $supplier_id, 'company_id' => creatorId(), 'is_active' => 1])->first();
                    if (!is_null($suppliers)) {
                        $component_id = [];

                        if (!empty($suppliers->components_id)) {
                            $component_id = explode(",", $suppliers->components_id);
                        }
                        $component_id[] = $components->id;
                        $suppliers->update(['components_id' => implode(',', $component_id)]);
                    }
                }

                $post = $request->all();
                unset($post['_token'], $post['name']);

                foreach ($post as $key => $data) {
                    if (gettype($data) == 'array') {

                        $data_val = array_values($data);
                        $data_id = array_keys($data);
                        if (count($data_id)) {
                            $ComponentsField = ComponentsField::where(['is_active' => 1, 'name' => $data_id[0], 'module' => 'Components'])->first();
                            if (!is_null($ComponentsField)) {

                                $data_val = $data[$data_id[0]];
                                if (!empty($data_val) && $data_val != 'undefined') {

                                    if ($ComponentsField->type == 'date') {
                                        $data_val = date("Y-m-d", strtotime($data_val));
                                    }

                                    if (is_file($data_val)) {
                                        $image_size = $data_val->getSize();


                                        $file_name = $data_val->getClientOriginalName();
                                        $file_path = $components->id . "_" . md5(time()) . "_" . $data_val->getClientOriginalName();
                                        $data_val->storeAs('Components', $file_path);
                                        $data_val = $file_path;
                                    }

                                    $custom_field_values = ComponentsFieldValues::create([
                                        'record_id' => $components->id,
                                        'field_id' => $ComponentsField->id,
                                        'value' => $data_val,
                                        'created_by' => $objUser->id,
                                        'company_id' => creatorId(),
                                        'workspace' => getActiveWorkSpace()
                                    ]);
                                }
                            }
                        }
                    }
                }
                $is_success = true;
                return redirect()->back()->with('success', __('Components Add Successfully') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
            } else {
                $message = __('Something went wrong.');
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
        } else {
            $message = __('Permission Denied.');
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
        if (Auth::user()->isAbleTo('components show')) {
            $Components = Component::where(['id' => $id])->first();

            if (!is_null($Components) && $Components->is_active) {
                $module_components = 'components';
                $module_pms = 'pms';


                $ComponentsField = DB::table('components_field_values')->leftJoin('components_fields', 'components_field_values.field_id', '=', 'components_fields.id')->where(['record_id' => $id])->get();

                $parts = DB::table('components')->leftJoin('parts', function ($join) {
                    $join->whereRaw(DB::raw("find_in_set(parts.id,components.parts_id)"));
                })->where('parts.id', '!=', null)->where('components.id', $Components->id)->get();


                $pms = DB::table('components')->leftJoin('pms', function ($join) {
                    $join->whereRaw(DB::raw("find_in_set(pms.id,components.pms_id)"));
                })->where('pms.id', '!=', null)->where('components.id', $Components->id)->get();

                $suppliers = DB::table('components')->leftJoin('suppliers', function ($join) {
                    $join->whereRaw(DB::raw("find_in_set(suppliers.id,components.supplier_id)"));
                })->where('suppliers.id', '!=', null)->where('components.id', $Components->id)->get();

                $wos = Workorder::where('components_id', $id)->get();

                $chartData = $this->getChartData(['duration' => 'year', 'components_id' => $id]);
                $Components_file = ComponentsFieldValues::where(['record_id' => $id, 'field_id' => 15])->get();
                $Components_Warranty_document = ComponentsFieldValues::where(['record_id' => $id, 'field_id' => 14])->first();

                $componentslogtime = ComponentsLogTime::where(['components_id' => $id])->get();

                $barcode  = [
                    'barcodeType' =>  'Data Matrix',
                    'barcodeFormat' => 'css',
                    'link' =>   route('component.show', [$id]),


                ];


                return view('cmms::component.view', compact('Components', 'chartData', 'ComponentsField', 'componentslogtime', 'parts', 'pms', 'module_components', 'module_pms', 'Components_file', 'suppliers', 'wos', 'Components_Warranty_document', 'barcode'));
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
        if (Auth::user()->isAbleTo('components edit')) {
            $ComponentsField = ComponentsField::where(['module' => 'Components'])->get();
            $Components = Component::where('id', $id)->where('workspace', getActiveWorkSpace())->first();

            $location = Location::where('company_id', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            $ComponentsFieldValues = DB::table('components_field_values')
                ->join('components_fields', 'components_field_values.field_id', '=', 'components_fields.id')
                ->where('record_id', $id)
                ->get();

            if ($ComponentsFieldValues->isEmpty()) {
                $ComponentsFieldValues = [];
            } else {
                foreach ($ComponentsFieldValues as $Components_Field_Values) {
                    $ComponentsField_Values[] = [$Components_Field_Values->name => $Components_Field_Values->value];
                }
                $ComponentsFieldValues = array_merge(...$ComponentsField_Values);
            }

            return view('cmms::component.edit', compact('Components', 'ComponentsField', 'ComponentsFieldValues', 'location'));
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
        if (Auth::user()->isAbleTo('Components edit')) {


            $Components = Component::where('id', $id)->first();
            if ($request->hasFile('thumbnail')) {

                $file_path = $Components->thumbnail;
                $image_size = $request->file('thumbnail')->getSize();


                $filenameWithExt = time() . '_' . $request->file('thumbnail')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('thumbnail')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $dir        = 'Components/thumbnail/';
                $url = '';
                $path = upload_file($request, 'thumbnail', $filenameWithExt, $dir, []);
                if ($path['flag'] == 1) {
                    $url = $path['url'];
                } else {
                    return redirect()->back()->with('error', __($path['msg']));
                }
                $Components['thumbnail'] = $url;
            }
        }
        if ($request->name) {
            $Components['name'] = $request->name;
        }

        if ($request->sku) {
            $Components['sku'] = $request->sku;
        }
        $objUser            = Auth::user();
        $Components['location_id'] = $request->location;
        $Components['created_by'] = $objUser->id;
        $Components['company_id'] = creatorId();
        Component::where('id', $id)->update($Components);

        event(new UpdateComponent($request, $Components));

        $post = $request->all();

        $ComponentsFieldValues = DB::table('components_field_values')
            ->join('components_fields', 'components_field_values.field_id', '=', 'components_fields.id')
            ->get();


        $post = $request->all();
        unset($post['_token'], $post['name'], $post['sku'], $post['_method'], $post['thumbnail'], $post['location']);

        foreach ($post as $datas) {
            foreach ($ComponentsFieldValues as $ComponentsField_Values) {

                if ($datas != null && array_key_exists($ComponentsField_Values->name, $datas)) {

                    $data['value'] = $datas[$ComponentsField_Values->name];

                    if (is_file($data['value'])) {

                        $file_path = '/' . 'Components/' . $ComponentsField_Values->value;
                        $image_size =  $data['value']->getSize();


                        $file_name = $data['value']->getClientOriginalName();
                        $file_path = $Components->id . "_" . md5(time()) . "_" . $data['value']->getClientOriginalName();
                        $data['value']->storeAs('Components', $file_path);
                        $data['value'] = $file_path;
                    }
                    ComponentsFieldValues::where('record_id', $ComponentsField_Values->record_id)->where('field_id', $ComponentsField_Values->field_id)->update($data);
                }
            }

            return redirect()->back()->with('success', __('Components Updated Successfully') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
        }
    }
    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (\Auth::user()->isAbleTo('components delete')) {
            $components = Component::where(['id' => $id])->first();

            if (!empty($components->thumbnail)) {
                delete_file($components->thumbnail);
            }
            $components->delete();

            event(new DestroyComponent($components));

            if ($components) {

                $ComponentsField = ComponentsFieldValues::where(['record_id' => $id])->first();

                $ComponentsFieldValues = ComponentsFieldValues::where(['record_id' => $id])->delete();
                $Components = Component::where(['id' => $id])->delete();

                return redirect()->back()->with('success', __('Components deleted successfully.'));
            } else {
                return redirect()->back()->with('error', __('Something went wrong.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function getChartData($arrParam)
    {
        $arrDuration = [];
        $components_id = $arrParam['components_id'];
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
            $data = ComponentsLogTime::select(\DB::raw('sum(hours) as total_hours'))->where('components_id', $components_id)->whereMonth('created_at', date('m', strtotime($label)))->whereYear('created_at', $year)->first();
            if ($data->total_hours != null) {
                $final_data = $data->total_hours;
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


        $objUser            = Auth::user();
        $currentlocation = Location::userCurrentLocation();


        $image_size = $request->file('file')->getSize();


        $file_name = $request->file->getClientOriginalName();

        $file_path = $request->lead_id . "_" . md5(time()) . "_" . $request->file->getClientOriginalName();

        $dir = 'documents_files/';
        $path = upload_file($request, 'file', $file_path, $dir, []);
        if ($path['flag'] == 1) {
            $file = $path['url'];
        } else {
            return redirect()->back()->with('error', __($path['msg']));
        }


        $file = ComponentsFieldValues::create(
            [

                'record_id' => $id,
                'field_id' => 15,
                'value' => $file_path,
                'location_id' => $currentlocation,
                'created_by' => $objUser->id,
                'company_id' => creatorId(),
            ]
        );

        $return               = [];
        $return['is_success'] = true;
        $return['download']   = route(
            'component.file.download',
            [$file->id]
        );

        $return['delete']     = route(
            'component.file.delete',
            [$file->id]
        );

        $return               = [];
        $return['is_success'] = true;
        $return['status'] = 1;
        $return['success_msg'] = __('Attachment Created Successfully') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : '');

        return response()->json($return);
    }

    public function fileDownload($id)
    {

        $file = ComponentsFieldValues::find($id);
        if ($file) {
            $file_path = base_path() . $file->value;
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

        $file = ComponentsFieldValues::find($id);

        if ($file) {
            $file_path = 'documents_files/' . $file->value;


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

    public function associatecomponentView($module, $id)
    {
        $objUser            = Auth::user();
        $currentlocation = Location::userCurrentLocation();

        //vendros detail page in assets
        if ($module == 'suppliers') {
            if (Auth::user()->isAbleTo('components associate')) {
                if ($currentlocation == 0) {
                    return redirect()->back()->with('error', __('Current location is not available.'));
                }
                $supplier_parts = Supplier::find($id);
                $components = Component::where(['company_id' => creatorId(), 'location_id' => $currentlocation, 'is_active' => 1])->whereNotIn('id', explode(',', $supplier_parts->components_id))->get()->pluck('name', 'id');
                $supplier_id = $id;
                return view('cmms::component.associate', compact('components', 'supplier_id', 'id', 'module'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
        //parts detail page in assets
        elseif ($module == 'parts_component') {
            if (Auth::user()->isAbleTo('components associate')) {
                if ($currentlocation == 0) {
                    return redirect()->back()->with('error', __('Current location is not available.'));
                }
                $parts = Part::find($id);
                $components = Component::where(['company_id' => creatorId(), 'location_id' => $currentlocation, 'is_active' => 1])->whereNotIn('id', explode(',', $parts->assets_id))->get()->pluck('name', 'id');
                $parts_id = $id;

                return view('cmms::component.associate', compact('components', 'parts_id', 'id', 'module'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        }
    }


    public function associatecomponent(Request $request, $module, $id)
    {
        $objUser            = Auth::user();
        $currentlocation = Location::userCurrentLocation();
        //vendros deatil page in assets
        if ($module == 'suppliers') {

            $Supplier = Supplier::where(['id' => $id, 'company_id' => creatorId(), 'is_active' => 1])->first();
            if (!is_null($Supplier)) {

                $supplierr_part_id = empty($Supplier->components_id) ? implode(',', $request->associate_parts)  : $Supplier->components_id . ',' . implode(',', $request->associate_parts);
                $Supplier->components_id = $supplierr_part_id;
                $Supplier->save();

                return redirect()->back()->with(['success' => __('Component associate to supplier successfully.'), 'tab-status' => 'components']);
            } else {

                return redirect()->back()->with(['error' => __('Components is not available.'), 'tab-status' => 'components']);
            }
        }
        //parts detail page in asset
        elseif ($module == 'parts_component') {
            $Parts = Part::where(['id' => $id, 'company_id' => creatorId(), 'is_active' => 1])->first();
            if (!is_null($Parts)) {

                $parts_components_id = empty($Parts->components_id) ? implode(",", $request->associate_parts) : $Parts->components_id . ',' . implode(',', $request->associate_parts);
                $Parts->components_id = $parts_components_id;
                $Parts->save();

                return redirect()->back()->with(['success' => __('Component associate to parts successfully.'), 'tab-status' => 'component']);
            } else {

                return redirect()->back()->with(['error' => __('Component is not available.'), 'tab-status' => 'component']);
            }
        } else {
            return redirect()->back()->with('error', __('Something went to wrong.'));
        }
    }


    public function removeAssociatecomponent(Request $request, $module, $id)
    {
        //vendors detail page in asset
        if ($module == "suppliers") {

            $Supplier = Supplier::where(['id' => $request->supplier_id, 'is_active' => 1])->first();
            $supplier_part_id = explode(',', $Supplier->componenas_id);
            unset($supplier_part_id[array_search($id, $supplier_part_id)]);
            $supplier_part_id = array_filter($supplier_part_id);
            $Supplier->components_id = implode(',', $supplier_part_id);
            $Supplier->save();

            return redirect()->back()->with(['success' => __('Associated component successfully deleted.'), 'tab-status' => 'component']);
        }
        //parts detail page in asset
        elseif ($module == "parts") {

            $Parts = Part::where(['id' => $request->parts_id, 'is_active' => 1])->first();

            $component_part_id = explode(',', $Parts->components_id);
            unset($component_part_id[array_search($id, $component_part_id)]);
            $component_part_id = array_filter($component_part_id);
            $Parts->components_id = implode(',', $component_part_id);
            $Parts->save();

            return redirect()->back()->with(['success' => __('Associated component successfully deleted.'), 'tab-status' => 'component']);
        } else {
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }
    //
}
