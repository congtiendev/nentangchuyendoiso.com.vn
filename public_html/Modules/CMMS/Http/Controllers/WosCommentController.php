<?php

namespace Modules\CMMS\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\CMMS\Entities\Location;
use Modules\CMMS\Entities\WosComment;
use Illuminate\Support\Facades\Auth;

class WosCommentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(Request $request)
    {
        $wo_id = $request->wo_id;
        return view('cmms::workorder.comment_create', compact('wo_id'));
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

        if ($request->file) {
                

            $filenameWithExt = time() . '_' .$request->file('file')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('file')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

           

            $dir        = 'wos_comment/';
            $url = '';
            $path = upload_file($request,'file',$filenameWithExt,$dir,[]);
            if($path['flag'] == 1){
                $url = $path['url'];
                }else{
                return redirect()->back()->with('error', __($path['msg']));
            }
        }
        $Woscomment = WosComment::create([
            'wo_id' => $request->wo_id,
            'description' => $request->description,
            'file' => !empty($url) ? $url : '',
            'location_id' => $currentlocation,
            'created_by' => $objUser->id,
            'company_id' => creatorId(),
            'workspace' => getActiveWorkSpace()
        ]);



        return redirect()->back()->with(['success'=> __('Comment created successfully.'),'tab-status' => 'comment']);
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
        $woscomment = WosComment::find($id);
    
        if($woscomment)
        {
            $woscomment->delete();

            return redirect()->back()->with(['success'=> __('Comment Deleted successfully.'),'tab-status' => 'comment']);
            
        }
        else
        {
            return redirect()->back()->with(['error' => __('Something is wrong.'),'tab-status' => 'comment']);
        }
    }

}
