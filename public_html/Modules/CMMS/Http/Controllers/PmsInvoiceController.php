<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\CMMS\Entities\Location;
use Illuminate\Support\Facades\Validator;
use Modules\CMMS\Entities\PmsInvoice;

class PmsInvoiceController extends Controller
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
        $pms_id = $request->pms_id;
        return view('cmms::pms.pmsinvoice_create', compact('pms_id'));
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
            'invoice_cost' => 'required',
        ];

        $validator = Validator::make($request->all(), $valid);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $attached_invoice = '';
        if ($request->hasFile('invoice')) {
            $attach_invoice = $request->file('invoice');
            //     $attach_invoice->storeAs('pms_invoice', $attached_invoice);

            $attached_invoice = md5(time()) . "_" . $attach_invoice->getClientOriginalName();
            $filename        = pathinfo($attached_invoice, PATHINFO_FILENAME);
            $extension       = $request->file('thumbnail')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            $dir        = 'pms_invoice';
            $url = '';
            $path = upload_file($request, 'pms_invoice', $filenameWithExt, $dir, []);
            if ($path['flag'] == 1) {
                $url = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
        }

        $pmsinvoice = PmsInvoice::create([
            'pms_id' => $request->pms_id,
            'invoice_cost' => $request->invoice_cost,
            'description' => $request->description,
            'invoice_file' => $url ?? '',
            'location_id' => $currentlocation,
            'created_by' => $objUser->id,
            'company_id' => creatorId(),
            'workspace'  => getActiveWorkSpace()
        ]);

        if ($pmsinvoice) {
            return redirect()->back()->with('success', __('Invoice created successfully.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
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
        $pmsinvoice = PmsInvoice::find($id);
        return view('cmms::pms.pmsinvoice_edit', compact('pmsinvoice'));
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

        $attached_invoice = '';
        if ($request->hasFile('invoice')) {

            $pms_invoice = PmsInvoice::find($id);


            $file_path = 'pms_invoice/' . $pms_invoice->invoice_file;


            $attach_invoice = $request->file('invoice');
            $attached_invoice = md5(time()) . "_" . $attach_invoice->getClientOriginalName();
            $attach_invoice->storeAs('pms_invoice', $attached_invoice);

            if (!empty($attached_invoice) && $request->hasFile('invoice') != "") {
                $pmsinvoice['invoice_file']     = $attached_invoice;
            }
        }


        $pmsinvoice['pms_id']        = $request->pms_id;
        $pmsinvoice['invoice_cost']  = $request->invoice_cost;
        $pmsinvoice['description']   = $request->description;
        $pmsinvoice['location_id']   = $currentlocation;
        $pmsinvoice['created_by']    = $objUser->id;

        $pmsinvoices = PmsInvoice::where('id', $id)->update($pmsinvoice);

        return redirect()->back()->with('success', __('Invoice update successfully.') . ((isset($result) && $result != 1) ? '<br> <span class="text-danger">' . $result . '</span>' : ''));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {

        $pmsinvoice = PmsInvoice::find($id);
        if ($pmsinvoice) {

            $pmsinvoice->delete();
            return redirect()->back()->with('success', __('Pms Invoice successfully deleted .'));
        } else {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }
    }
}
