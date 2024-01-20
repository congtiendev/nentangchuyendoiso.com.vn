<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Crypt;
use Modules\CMMS\Entities\Location;
use Modules\CMMS\Entities\Workorder;
use Modules\CMMS\Entities\Component;
use Modules\CMMS\Entities\WorkOrderImage;
use App\Models\EmailTemplate;
use Modules\CMMS\Events\CreateWorkrequest;


class WorkrequestController extends Controller
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
        $user = Auth::user();

    
        $location_id = Crypt::decrypt($request->location_id);
        
        if (!Location::where('id', $location_id)->exists()) {
            return redirect()->back()->with('error', __('Something went to wrong'));
        }
        $location = location::find($location_id);

        $workorder = Workorder::create([
            'wo_id'         => time(),
            'components_id' => $request->components_id,
            'wo_name'       => $request->wo_name,
            'instructions'  => $request->instructions,
            'user_name'     => $request->user_name,
            'user_email'    => $request->user_email,
            'priority'      => 'Low',
            'work_status'   => 'Open',
            'location_id'   => $location_id,
            'company_id'    => $location->company_id,
            'workspace'     => $location->workspace,
        ]);

        event(new CreateWorkrequest($request,$workorder));

        $problem = Component::where('id' , $workorder->components_id)->pluck('name', 'id');
    

    
        if ($request->hasFile('file')) {
            if ($workorder) {
                $files = $request->file;
                foreach ($files as $key => $file) {



                    $filenameWithExt = $request->file('file')[$key]->getClientOriginalName();
                    $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    $extension       = $request->file('file')[$key]->getClientOriginalExtension();
                    $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                        $dir        = 'workorder_files/';
                        $path = multi_upload_file($file, 'file', $fileNameToStore, $dir);

                        if ($path['flag'] == 1) {
                            $url = $path['url'];
                        } else {
                            return redirect()->back()->with('error', __($path['msg']));
                        }

                        $parts = WorkOrderImage::create([
                            'wo_id'       => $workorder->id,
                            'image'       => $fileNameToStore,
                            'location_id' => $location_id,
                            'company_id'  => $location->company_id,
                            'work_status' => 'open',
                        ]);
                }
                if(!empty(company_setting('Work Order Request' ,$location->company_id)) && company_setting('Work Order Request' ,$location->company_id)  == true)
                {
                    // $supplier = Supplier::find($Pos->supplier_id);
                    $component = Component::find($request->components_id);
                    $user = User::find($location->company_id);
                    $uArr = [
                        'work_request_name' => $request->wo_name,
                        'email' => $request->user_email,
                        'problem' => $component->name,
                        'instruction'=>$request->instructions,
                    ];
                    try
                    {
                        $resp = EmailTemplate::sendEmailTemplate('Work Order Request', [$user->email], $uArr , $user->id);
                    }
                    catch(\Exception $e)
                    {
                        $resp['error'] = $e->getMessage();
                    }
                    
                    return redirect()->back()->with('success', __('Work request submitted successfully.'). ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                }

                return redirect()->back()->with('success', __('Work Request submitted successfully.'));
            }
        }


        if ($workorder) {
            return redirect()->back()->with('success', __('Work order created successfully.'));
        } else {
            return redirect()->back()->with('error', __('Something went wrong.'));
        }
    }

    public function QRCode($id)
    {
        return view('cmms::work_request.QRCodelink' , compact('id'));
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
}
