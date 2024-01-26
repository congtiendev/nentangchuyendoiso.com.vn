<?php

namespace App\Http\Controllers;

use App\Models\BorrowAssetRecord;
use App\Models\BorrowEmployeeRecord;
use App\Models\ManagerFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ManagerFileControler extends Controller
{
    public function index()
    {
        $data = BorrowEmployeeRecord::query()->latest()->get();
        return view('manager.index',compact('data'));
    }
    public function index2()
    {
        $data = BorrowAssetRecord::query()->latest()->get();
        return view('manager.index2',compact('data'));
    }

    public function create() 
    {
        return view('manager.create');
    }

    public function store(Request $request)
    {

        $valid = [
            'name' => 'required',
            'type' => 'required',
        ];

        $validator = Validator::make($request->all(), $valid);
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        }
        try {
            $user_id = Auth::user()->id;
            $data = ManagerFile::create([
                'user_id' => $user_id,
                'name' => $request->name,
                'type' => $request->type,
                'status' => 0,
                'created_at' => now()
            ]);
            return redirect()->route('manager-file.index')->with('success', __('Tạo mới hồ sơ mượn thành công'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Tạo mới hồ sơ mượn thất bại'));
        }

    }

    public function edit($id)
    {
        $manager = ManagerFile::find($id)->first();
        return view('manager.edit', compact('manager'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'type' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {
            $messages = $validator->getMessageBag();

            return redirect()->back()->with('error', $messages->first());
        }
        try {
            $manager = ManagerFile::find($request->id);
            $manager->user_id = Auth::user()->id;
            $manager->name = $request->name;
            $manager->type = $request->type;
            $manager->status = $request->status;
            $manager->updated_at = now();
            $manager->save();
            return redirect()->route('manager-file.index')->with('success', __('Cập nhật hồ sơ mượn thành công'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', __('Cập nhật hồ sơ mượn thất bại'));
        }
    }
}
