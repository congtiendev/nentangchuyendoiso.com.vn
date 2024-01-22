<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Employee;
use App\Models\User;
use App\Models\WorkShift;
use App\Models\WorkshiftTypes;
use App\Models\WorkshiftApproval;
use App\Models\WorkshiftDepartment;
use Modules\Hrm\Entities\Department;
use Modules\Hrm\Entities\Branch;
use Carbon\Carbon;
use DB;

class WorkShiftDepartmentController  extends Controller
{
    public function index()
    {
        if(Auth::user()->type == 'company' || Auth::user()->type == 'hr'){
            $employees = Employee::all();
            $workShiftTypes = WorkshiftTypes::all();
            $departments = Department::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
            $workShifts = WorkShiftDepartment::join('departments', 'departments.id', '=', 'workshift_department.department_id')
                ->select('workshift_department.*', 'departments.name as department_name', 'departments.id as department_id')
                ->groupBy('workshift_department.department_id')
                ->get();
            return view('hrm::workshift_department.index', compact('workShifts', 'employees', 'workShiftTypes', 'departments'));
        } else {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập !');
        }
    }

    public function addWorkshiftDepartment(Request $request)
    {
        $data = $request->all();
        $workShifts = WorkShiftDepartment::where('department_id', $data['department_id'])->first();
        $employees = Employee::where('department_id', $data['department_id'])->where('workspace', getActiveWorkSpace())->get();
        if ($workShifts) {
            return redirect()->back()->with('error', 'Phòng ban này đã được thêm vào ca làm việc !');
        } else {
            try {
                WorkShiftDepartment::create([
                    'department_id' => $data['department_id'],
                ]);

                foreach ($employees as $employee) {
                    WorkShift::create([
                        'user_id' => $employee->user_id,
                    ]);
                }
                return redirect()->back()->with('success', 'Thêm phòng ban vào ca làm việc thành công !');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Có lỗi xảy ra : ' . $e->getMessage());
            }
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $employees = Employee::where('department_id', $data['department_id'])->where('workspace', getActiveWorkSpace())->get();
        try {
            foreach ($data['shifts'] as $departmentId => $shifts) {
                foreach ($shifts as $date => $shift) {
                    WorkShiftDepartment::updateOrCreate(
                        ['date' => $date, 'department_id' => $departmentId],
                        ['shift' => $shift]
                    );
                    foreach ($employees as $employee) {
                        WorkShift::updateOrCreate(
                            ['date' => $date, 'user_id' => $employee->user_id],
                            ['shift' => $shift]
                        );
                    }
                }
            }
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(string $id)
    {
        $workShiftDepartment =    WorkShiftDepartment::find($id);
        if (!$workShiftDepartment) {
            return redirect()->back()->with('error', 'Không tìm thấy ca làm việc !');
        }
        try {
            $workShiftDepartment->delete();
            return redirect()->back()->with('success', 'Xóa ca làm việc thành công !');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Có lỗi xảy ra : ' . $e->getMessage());
        }
    }
}