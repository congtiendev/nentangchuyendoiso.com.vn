<?php

namespace App\Http\Controllers;

use App\Models\BorrowAssetRecord;
use App\Models\User;
use Faker\Provider\ar_EG\Company;
use Illuminate\Http\Request;
use Modules\Assets\Entities\Asset;

class BorrowAssetRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = BorrowAssetRecord::query()->latest()->get();
        return view('brrow-asset-records.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $assets = Asset::where('workspace_id', getActiveWorkSpace())
        ->pluck('name','id');
        $users = User::where('created_by', creatorId())
             ->where('workspace_id', getActiveWorkSpace())
             ->pluck('name', 'id');
        return view('brrow-asset-records.create',compact('assets','users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (\Auth::user()->isAbleTo('proposal create')) {
        
            $validator = \Validator::make(
                $request->all(),
                [
                    'asset_id' => 'required',
                    'user_id' => 'required',
                    'name' => 'required',
                    'borrowed_day' => 'required',
                    'borrowed_date' => 'required',
                    'give_back_day' => 'required',
                    'description' => 'required',

                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first())->withInput();
            }
                BorrowAssetRecord::create([
                'name' => $request->input('name'),
                'user_id' => $request->input('user_id'),
                'asset_id' => $request->input('asset_id'),
                'borrowed_date' => $request->input('borrowed_date'),
                'borrowed_day' => $request->input('borrowed_day'),
                'give_back_day' => $request->input('give_back_day'),
                'description' => $request->input('description'),
            ]);
            return redirect()->route('borrow-asset-records.index')->with('success', __('Thêm mới hồ sơ mượn DC thành công.'));

        }else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(BorrowAssetRecord $borrowAssetRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BorrowAssetRecord $borrowAssetRecord)
    {
        $assets = Asset::query()->pluck('name','id');
        $users = User::where('created_by', creatorId())
             ->where('workspace_id', getActiveWorkSpace())
             ->pluck('name', 'id');
        return view('brrow-asset-records.edit', compact('borrowAssetRecord','assets','users'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BorrowAssetRecord $borrowAssetRecord)
    {
        
            $validator = \Validator::make(
                $request->all(),
                [
                    'asset_id' => 'required',
                    'user_id' => 'required',
                    'name' => 'required',
                    'borrowed_day' => 'required',
                    'borrowed_date' => 'required',
                    'give_back_day' => 'required',
                    'description' => 'required',
                    'status' => 'required', // Make sure 'status' is required in the update as well
                ]
            );
    
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
    
            // Update the BorrowEmployeeRecord with the new data
            $borrowAssetRecord->update([
                'name' => $request->input('name'),
                'user_id' => $request->input('user_id'),
                'asset_id' => $request->input('asset_id'),
                'borrowed_date' => $request->input('borrowed_date'),
                'borrowed_day' => $request->input('borrowed_day'),
                'give_back_day' => $request->input('give_back_day'),
                'description' => $request->input('description'),
                'status' => $request->input('status'),
            ]);
            return redirect()->route('borrow-asset-records.index')->with('success', __('Cập nhật văn bản thành công.'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BorrowAssetRecord $borrowAssetRecord)
    {
        $borrowAssetRecord->delete();
        return back()->with('success', 'Record deleted successfully');
    }
}
