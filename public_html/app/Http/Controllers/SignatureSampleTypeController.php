<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SignatureSampleType;

class SignatureSampleTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $signatureSampleTypes = SignatureSampleType::where('workspace', getActiveWorkSpace())->get();
        return view('signature_sample_type.index', compact('signatureSampleTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            SignatureSampleType::create([
                'name' => $request->name,
                'description' => $request->description ?? 'Trống',
                'workspace' => getActiveWorkSpace(),
            ]);
            return redirect()->back()->with('success', "Thêm loại trình ký mẫu thành công!");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // 
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $signatureSampleType = SignatureSampleType::find($id);
        try {
            $signatureSampleType->update($request->all());
            return redirect()->back()->with('success', "Cật nhật loại trình ký mẫu thành công!");
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $signatureSampleType = SignatureSampleType::find($id);
        try {
            $signatureSampleType->delete();
            return redirect()->back()->with('success', "Xóa loại trình ký mẫu thành công!");
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', __('Something went wrong'));
        }
    }
}
