<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMMS\Entities\Form;
use Illuminate\Support\Facades\Auth;

class FormController extends Controller
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
    public function create()
    {
        return view('cmms::create');
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

    public function designUpdate(Request $request, $pms_id)
    {
        $json_data = json_decode($request->json);
        $all_json_data = [];
        foreach ($json_data as $key => $data) {
            $json = $data;
            if ($data->type == 'file') {

              
                $Form = Form::where('pms_id', $pms_id)->first();
                if (!(empty($Form))) {
                    $updated_name = $data->name . '-preview';
                    
                    if (!empty($request->$updated_name) && !empty($data->name)) {
                        // if file added then existing json in file uploaded data add
                        $data->orijanal_name = $request->$updated_name->getClientOriginalName();
                        $data->extension = $request->$updated_name->extension();
                        $timestamp_name = time() . '_' . $data->orijanal_name;
                        $dir        = 'instruction/';
                      
                        $path = upload_file($request,$updated_name,$timestamp_name,$dir,[]);
                        $url = '';
                        $data->timestamp_name=$url;

                        if($path['flag'] == 1){
                            $url = $path['url'];
                        }else{
                            return redirect()->back()->with('error', __($path['msg']));
                        }
                        // $data->path = $request->$updated_name->storeAs('instruction', time() . '_' . $data->orijanal_name);



                        $json = $data;
                    } 
                    // else {
                    //     //  if file not added then existing json get and replace
                    //     $json = json_decode($Form->json, true)[$key - 1];
                    // }
                }
            }
            $json_data[$key] =  $json;
        }
        $request_json['json'] = json_encode($json_data);
        $request->merge($request_json);

        if (Form::where('pms_id', $pms_id)->exists()) {

            $form['json'] = $request->json;
            $user['created_by'] = creatorId();
            $user['company_id'] = creatorId();
            $user['workspace'] = getActiveWorkSpace();

            Form::where('pms_id', $pms_id)->update($form);
            return redirect()->back()->with(['success' => __('Form successfully updated!'), 'tab-status' => 'instraction']);
        } else {

            $user               = new Form();
            $user['pms_id']     = $pms_id;
            $user['json']       = $request->json;
            $user['created_by'] = creatorId();
            $user['company_id'] = creatorId();
            $user['workspace'] = getActiveWorkSpace();

            $user->save();


            return redirect()->back()->with(['success' => __('Instraction form successfully Add'), 'tab-status' => 'instraction']);
        }
    }
}
