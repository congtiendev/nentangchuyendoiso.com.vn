<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMMS\Entities\Location;
use Modules\CMMS\Entities\CmmsPos;
use Illuminate\Support\Facades\Auth;
use Modules\CMMS\Entities\Supplier;
use Modules\CMMS\Entities\Part;
use Modules\CMMS\Entities\CmmsPosPart;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\EmailTemplate;
use Modules\CMMS\Events\CreateCmmspos;
use Modules\CMMS\Events\DestroyCmmspos;
use Modules\CMMS\Events\UpdateCmmspos;
use App\Models\CustomNotification;
use App\Models\UserNotifications;
class CmmsPosController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $objUser            = Auth::user();
        $currentLocation = Location::userCurrentLocation();
        $locations = Location::where('workspace',getActiveWorkSpace())->where('created_by',creatorId())->get()->pluck('name', 'id');

        if (Auth::user()->type == 'company')
        {
            $pos = DB::table('cmms_pos')
                ->join('users', 'cmms_pos.user_id', '=', 'users.id')
                ->join('suppliers', 'cmms_pos.supplier_id', '=', 'suppliers.id')
                ->select(DB::raw('cmms_pos.*, users.name as user_name, suppliers.name as supplier_name'))
                ->where(['cmms_pos.location_id' => $currentLocation, 'cmms_pos.is_active' => 1])
                ->get();

        }
        else 
        {
            $pos = DB::table('cmms_pos')
                ->join('users', 'cmms_pos.user_id', '=', 'users.id')
                ->join('suppliers', 'cmms_pos.supplier_id', '=', 'suppliers.id')
                ->select(DB::raw('cmms_pos.*, users.name as user_name, suppliers.name as supplier_name'))
                ->where(['cmms_pos.location_id' => $currentLocation, 'cmms_pos.is_active' => 1 , 'cmms_pos.user_id' => $objUser->id])
                ->get();
            
        }

        $location_name = CmmsPos::where('company_id', creatorId())->where('workspace', getActiveWorkSpace())->where('location_id',$currentLocation)->get();
        return view('cmms::pos.index' , compact('currentLocation' , 'locations' , 'pos' , 'location_name'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        $partsid = $request->partsid;
        $wo_id = $request->wo_id;
        $supplier_id = $request->supplier_id;
        $objUser            = Auth::user();
        $locations = Location::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
        $currentLocation = Location::userCurrentLocation();

        if ($supplier_id) {
            $Supplier = Supplier::where('id', $supplier_id)->where('location_id',$currentLocation)->get()->pluck('name', 'id');
        } else {
            $Supplier = Supplier::get()->where('company_id',creatorId())->where('location_id',$currentLocation)->pluck('name', 'id');
        }


        $Parts = Part::get()->where('company_id',creatorId())->where('location_id',$currentLocation)->pluck('name', 'id');
        $User = User::whereNotIn('type', ['company', 'client'])->where('created_by',creatorId())->where('workspace_id',getActiveWorkSpace())->get()->pluck('name', 'id');
        
        $invoice_number = 1;
        $customerId = 1;

        return view('cmms::pos.create', compact('Parts', 'User', 'Supplier', 'invoice_number', 'customerId', 'partsid', 'wo_id','supplier_id' , 'currentLocation' , 'locations'));
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

        // $validator = \Validator::make(
        //     $request->all(),
        //     [
        //         'supplier_id' => 'required',
        //         'user_id' => 'required',
        //         'pos_date' => 'required',
        //         'delivery_date' => 'required',
        //         'items' => 'required',
        //     ]
        // );

        // if ($validator->fails()) {
        //     $messages = $validator->getMessageBag();

        //     return redirect()->back()->with('error', $messages->first());
        // }
        $Pos                = new CmmsPos();

        if ($request->partsid) {
            $Pos->parts_id  = $request->partsid ??null;
        }
        if ($request->wo_id) {
            $Pos->wo_id         = $request->wo_id ??null;
        }
   

        $Pos->supplier_id     = $request->supplier_id ?? null;
        $Pos->user_id       = $request->user_id ?? null;
        $Pos->budgets_id    = $request->budgets_id ?? null;
        $Pos->pos_date      = $request->pos_date ?? null;
        $Pos->delivery_date = $request->delivery_date ?? null;
        $Pos->location_id   = $request->location ?? null;
        $Pos->created_by    = Auth::user()->id;
        $Pos->company_id    = creatorId();
        $Pos->workspace     = getActiveWorkSpace() ?? null;
        $Pos->save();
        $products = $request->items;

        for ($i = 0; $i < count($products); $i++) {
            $PosProduct              = new CmmsPosPart();
            $PosProduct->pos_id      = $Pos->id;
            $PosProduct->parts_id    = $products[$i]['item'];
            $PosProduct->description = $products[$i]['description'];
            $PosProduct->quantity    = $products[$i]['quantity'] ?? null;
            $PosProduct->price       = $products[$i]['price'] ?? null;
            $PosProduct->tax         = isset($products[$i]['tax']) ? $products[$i]['tax'] : 0;
            $PosProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $PosProduct->shipping    = $products[$i]['shipping'] ?? null;
            $PosProduct->location_id = $currentlocation ?? null;
            $PosProduct->created_by  = Auth::user()->id;
            $PosProduct->company_id  = creatorId();
            $PosProduct->workspace     = getActiveWorkSpace();
            $PosProduct->save();
        }
   
        event(new CreateCmmspos($request,$Pos));
        $notification_link = "";
        if(!empty($Pos->wo_id))
        {
            $notification_link = url('workorder/' . $Pos->wo_id . '#pos_sidebar');
        }
        else
        {
            $notification_link =  route('cmms_pos.index');
        }
        try {
            $notification = CustomNotification::create([
                'title' => "Đơn đặt hàng mới",
                'content' => "Đã đặt cho bạn một đơn hàng mới",
                'link' => $notification_link,
                'from' => $PosProduct->created_by,
                'send_to' => json_encode(array_map('intval',explode(',', $Pos->user_id))),
                'type' => 'new_order',
            ]);
                UserNotifications::create([
                    'user_id' => $Pos->user_id,
                    'notification_id' => $notification->id,
                    'is_read' => 0
                ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Lỗi : ') . $e->getMessage());
        }

        if(!empty(company_setting('New POs')) && company_setting('New POs')  == true)
        {
            $supplier = Supplier::find($request->supplier_id);
            $user = User::find($request->user_id);

            $items = Part::find($PosProduct->parts_id);
            $uArr = [
                'supplier' => $supplier->name,
                'items' => $items->name,
                'quantity' => $PosProduct->quantity,
                'price' =>  $PosProduct->price,
                'purchase_order_date' => $Pos->pos_date,
                'expected_delivery_date' => $Pos->delivery_date,
                'pos_description' => $PosProduct->description,
            ];
            try
            {
                $resp = EmailTemplate::sendEmailTemplate('New POs', [$supplier->email , $user->email], $uArr);
            }
            catch(\Exception $e)
            {
                $resp['error'] = $e->getMessage();
            }
            
            return redirect()->route('cmms_pos.index')->with('success', __('POs successfully created.'). ((!empty($resp) && $resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
        }

            return redirect()->route('cmms_pos.index')->with('success', __('POs successfully created.'));
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
    public function edit(Request $request , $ids)
    {
        {


            $locations = Location::where('created_by',creatorId())->where('workspace',getActiveWorkSpace())->get()->pluck('name', 'id');
            $currentLocation = Location::userCurrentLocation();

            $id      = Crypt::decrypt($ids);
            $parts = Part::get()->pluck('name', 'id');
            $Supplier = Supplier::get()->pluck('name', 'id');
            $user = User::whereNotIn('type', ['company', 'client'])->get()->pluck('name', 'id');
    
            $invoice_number = 1;
            $customerId = 1;
    
            $invoice = CmmsPos::find($id);
            $product_services = CmmsPosPart::where('pos_id', $id)->get();
    
            $partsid = $invoice->partsid;
            $wo_id = $invoice->wo_id;
            $supplier_id = $invoice->supplier_id;

            return view('cmms::pos.edit', compact('supplier_id','wo_id','partsid','parts', 'user', 'Supplier', 'invoice_number', 'customerId', 'invoice', 'product_services' , 'currentLocation' , 'locations'));
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
        $objUser            = Auth::user();
        $currentlocation = Location::userCurrentLocation();

        $validator = \Validator::make(
            $request->all(),
            [
                'supplier_id' => 'required',
                'user_id' => 'required',
                'pos_date' => 'required',
                'delivery_date' => 'required',
                'items' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }

        $Pos                = CmmsPos::find($id);
        $Pos->supplier_id     = $request->supplier_id;
        $Pos->user_id       = $request->user_id;
        $Pos->budgets_id    = $request->budgets_id;
        $Pos->pos_date      = $request->pos_date;
        $Pos->delivery_date = $request->delivery_date;
        $Pos->location_id   = $request->location;
        $Pos->created_by    = Auth::user()->id;
        $Pos->company_id    = creatorId();
        $Pos->save();

        event(new UpdateCmmspos($request,$Pos));

        $products = $request->items;
        $temp=[];
        foreach ($products as $key => $value) {
            if($value['id'] !==null)
            {
                $temp[]=$value['id'];
            }
           
        }
       
        $pos_data=CmmsPosPart::whereNotIn('id',$temp)->where('pos_id',$Pos->id)->delete();
        
       
        for ($i = 0; $i < count($products); $i++) {
            
            $PosProduct = CmmsPosPart::find($products[$i]['id']);
            if(empty($PosProduct))
            {
                $PosProduct = new CmmsPosPart();
              
            }
            
            if (isset($products[$i]['item'])) {

                $PosProduct->parts_id = $products[$i]['item'];
            }
            $PosProduct->pos_id      = $Pos->id;
            $PosProduct->description = $products[$i]['description'];
            $PosProduct->quantity    = $products[$i]['quantity'];
            $PosProduct->price       = $products[$i]['price'];
            $PosProduct->tax         = $products[$i]['tax'];
            $PosProduct->discount    = isset($products[$i]['discount']) ? $products[$i]['discount'] : 0;
            $PosProduct->shipping    = $products[$i]['shipping'];
            $PosProduct->location_id = $currentlocation;
            $PosProduct->created_by  = Auth::user()->id;
            $PosProduct->company_id  = creatorId();
            $PosProduct->save();
           
        }
       
        return redirect()->back()->with(['success' => __('POs successfully updated.'), 'tab-status' => 'pos']);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $Pos = CmmsPos::find($id);

        if ($Pos) {
                $pos_parts = CmmsPosPart::where('pos_id', $id)->delete();
                $Pos->delete();

        event(new DestroyPos($request,$Pos));

            return redirect()->back()->with(['success' => __('POs successfully deleted .'), 'tab-status' => 'pos']);
        } else {
            return redirect()->back()->with(['error' => __('Something is wrong.'), 'tab-status' => 'pos']);
        }
    }

    public function get_parts(Request $request){
        $id = $request->supplier_id;
        $supplier_parts = \DB::table('suppliers')->find($id);
        $parts = \DB::table('parts')->whereIn('id',explode(',',$supplier_parts->parts_id))->get();
  
        return response()->json(['parts' => $parts]);
    }

    public function getsupplier(Request $request)
    {
        if($request->location_id == 0)
        {
            $supplier = \Modules\CMMS\Entities\Supplier::get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $supplier  = \Modules\CMMS\Entities\Supplier::where('location_id',$request->location_id)->get()->pluck('name','id');
        }
        return response()->json($supplier);

    }

    public function getitems(Request $request)
    {
        if($request->location_id == 0)
        {
            $items = \Modules\CMMS\Entities\Part::get()->pluck('name', 'id')->toArray();
        }
        else
        {
            $items  = \Modules\CMMS\Entities\Part::where('location_id',$request->location_id)->get()->pluck('name','id');
        }
        return response()->json($items);

    }

    public function items(Request $request)
    {
        $items = CmmsPosPart::where('pos_id', $request->invoice_id)->where('parts_id', $request->product_id)->first();
        return json_encode($items);
    }

    public function product(Request $request)
    {

        $product_data = CmmsPosPart::find([$request->all()]);
        $data['taxRate']     = 10;
        $data['taxes']       = 10;
        $salePrice           = 10;
        $quantity            = 1;
        $taxPrice            = (10 / 100) * ($salePrice * $quantity);
        $data['totalAmount'] = 200;
        $data['price'] = $product_data[0]['price'];
        return json_encode($data);
    }
}
