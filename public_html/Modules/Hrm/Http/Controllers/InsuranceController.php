<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Department;
use Modules\Hrm\Entities\Designation;
use Exception;
use Modules\Hrm\Entities\Employee;
use Modules\Hrm\Events\CreateDesignation;
use App\Models\Insurance;

class InsuranceController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $designations = Designation::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->with(['branch', 'department'])->get();
        $insurances = Insurance::where('created_by', '=', creatorId())->paginate(10);
        return view('hrm::insurance.index', compact('designations', 'insurances'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('hrm::insurance.create');
    }

    public function store(Request $request)
    {
        try{
            Insurance::create([
                'created_by' =>  $request->created_by,
                'insurance_name' => $request->insurance_name,
                'designation_id' => $request->designation_id,
                'discount' => $request->discount
              ]);
              return redirect()->route('insurance.index')->with('success', "Thêm bảo hiểm thành công");
        }catch(Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */


    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit($id)
    {
        $designations = Designation::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->with(['branch', 'department'])->get();
        $insurance = Insurance::where('created_by', '=', creatorId())->where('id', $id)->first();
        return view('hrm::insurance.edit', compact('designations', 'insurance'));
    }

    public function update(Request $request, $id)
    {
        try {
            Insurance::where('id', $id)->update([
                'created_by' =>  $request->created_by,
                'insurance_name' => $request->insurance_name,
                'designation_id' => $request->designation_id,
                'discount' => $request->discount
            ]);
            return redirect()->route('insurance.index')->with('success', 'Cập nhật bảo hiểm thành công');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            Insurance::find($id)->delete();
            return redirect()->back()->with('success', 'Xóa bảo hiểm thành công');
        } catch (Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getDepartment(Request $request)
    {
        $designation_id = $request->designation_id;
        if ($designation_id) {
            $designation = Designation::find($designation_id);
            $department = Department::find($designation->department_id);
            $option = '<option value="' . $department->id . '">' . $department->name . '</option>';
            return response()->json(['success' => 1, 'option' => $option]);
        } else {
            return response()->json(['success' => 0]);
        }
    }
}
