<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\SignatureSample;
use App\Models\SignatureSampleType;
use Illuminate\Support\Facades\Auth;

class SignatureSampleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $signatureSamples = SignatureSample::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
        $signatureTypes = SignatureSampleType::where('workspace', getActiveWorkSpace())->get();
        return view('signature_sample.index', compact('signatureSamples', 'signatureTypes'));
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
        if ($request->hasFile('content')) {
            $filenameWithExt = $request->file('content')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('content')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;
            try {
                $uploadContent = multi_upload_file($request->file('content'), 'document', $fileNameToStore, 'emp_document');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Please upload file'));
        }
        $data = [
            'name' => $request->name,
            'content' => $uploadContent['url'],
            'signature_object' => $request->signature_object,
            'approver' => $request->approver,
            'description' => $request->description,
            'created_by' => Auth::user()->id,
            'signature_type' => $request->signature_type,
            'workspace' => getActiveWorkSpace(),
        ];
        try {
            SignatureSample::create($data);
            return redirect()->back()->with('success', 'Thêm trình ký mẫu thành công !');
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $signatureSample = SignatureSample::findOrfail($id);
        return view('signature_sample.show', compact('signatureSample'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $signatureSample = SignatureSample::find($id);
            delete_file($signatureSample->content);
            $signatureSample->delete();
            return redirect()->back()->with('success', 'Xóa trình ký mẫu thành công !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
