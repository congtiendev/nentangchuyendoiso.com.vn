<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\CMMS\Entities\WosInvoice;
use Modules\CMMS\Entities\Location;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use DB;

class WosInvoiceController extends Controller
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
        $wo_id = $request->wo_id; 
        return view('cmms::workorder.woinvoice_create', compact('wo_id'));
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
            return redirect()->back()->with('error', __('Current location is not available.'));
        }
        
        $valid = [
            'invoice_cost' => 'required',
            'invoice'      => 'required|mimes:jpeg,jpg,svg,png,doc,docx,pdf'    
        ];

        $validator = Validator::make($request->all(), $valid);
        if($validator->fails())
        {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

         $attached_invoice = '';
        if($request->invoice){

            $image_size = $request->file('invoice')->getSize();
             $filenameWithExt = $request->file('invoice')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('invoice')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
    
                
                $dir        = 'wos_invoice/';
                
                $image_path = $dir . $objUser['invoice'];
                if (\File::exists($image_path)) {
                    \File::delete($image_path);
                }
                $url = '';
                $path = upload_file($request,'invoice',$fileNameToStore,$dir,[]);
                
                if($path['flag'] == 1){
                    $url = $path['url'];
                }else{
                    return redirect()->back()->with('error', __($path['msg']));
                }
        }
        
        $wosinvoice = WosInvoice::create([
            'wo_id' => $request->wo_id,
            'invoice_cost' => $request->invoice_cost,
            'description'  => $request->description,
            'invoice_file' => !empty($url) ? $url : '',
            'location_id'  => $currentlocation,
            'created_by'   => $objUser->id,
            'company_id'   => creatorId(),
            'workspace'    => getActiveWorkSpace(),
        ]);

        if($wosinvoice){
            return redirect()->back()->with(['success'=> __('Invoice created successfully.') . ((isset($result) && $result!=1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''),'tab-status'=> 'invoice']);
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
        $wosinvoice = WosInvoice::find($id);
        return view('cmms::workorder.woinvoice_edit', compact('wosinvoice'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        {
            $objUser            = Auth::user();
            $currentlocation = Location::userCurrentLocation();
            if($currentlocation == 0){
                return redirect()->back()->with(['error'=> __('Current location is not available.'),'tab-status'=> 'invoice']);
            }
    
            $attached_invoice = '';
            if($request->hasFile('invoice'))
            {
                $wosinvoice = WosInvoice::find($id);
                $file_path =  $wosinvoice->invoice_file;   
                $image_size = $request->file('invoice')->getSize();
                
                $attach_invoice = $request->file('invoice');
                $attached_invoice =  md5(time()) . "_" . $attach_invoice->getClientOriginalName();
                $attach_invoice->storeAs('wos_invoice', $attached_invoice);
                    
            }
            $wosinvoices               = WosInvoice::where('id', $id)->first();
            $wosinvoices->wo_id        = $wosinvoices->wo_id;
            $wosinvoices->invoice_cost = $request->invoice_cost;
            $wosinvoices->description  = $request->description;
            $wosinvoices->location_id  = $currentlocation;
            $wosinvoices->created_by   = $objUser->id;
            $wosinvoices->invoice_file = !empty($attached_invoice) ? 'uploads/wos_invoice/' .$attached_invoice : $wosinvoices->invoice_file;
            $wosinvoices->company_id   = creatorId();
            $wosinvoices->workspace    = getActiveWorkSpace();
            $wosinvoices->save();
    
            return redirect()->back()->with(['success'=> __('Invoice update successfully.'). ((isset($result) && $result!=1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''),'tab-status'=> 'invoice']);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $wosinvoice = WosInvoice::find($id);

        if($wosinvoice)
        {
            $wosinvoice->delete();
            return redirect()->back()->with(['success'=> __('Work order Invoice successfully deleted.'),'tab-status'=> 'invoice']);
        }
        else
        {
            return redirect()->back()->with(['error'=> __('Something is wrong.'),'tab-status'=> 'invoice']);
        }
    }
}
