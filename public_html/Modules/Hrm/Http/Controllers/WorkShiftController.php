<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Employee;
use App\Models\User;
use App\Models\WorkShift;
use App\Models\WorkshiftApproval;
use Carbon\Carbon;
use DB;

class WorkShiftController extends Controller
{
  /**
   * Display a listing of the resource.
   * @return Response
   */
  public function index()
  {
    if (Auth::user()->isAbleTo('attendance manage')) {
      $employees = Employee::all();
      if (Auth::user()->type == 'company' || Auth::user()->type == 'hr') {
        $workShifts = WorkShift::join('users', 'users.id', '=', 'workshift.user_id')
          ->select('workshift.*', 'users.name as user_name', 'users.id as user_id')
          ->groupBy('workshift.user_id')
          ->get();
      } else {
        $workShifts = WorkShift::join('users', 'users.id', '=', 'workshift.user_id')
          ->select('workshift.*', 'users.name as user_name', 'users.id as user_id')
          ->where('workshift.user_id', Auth::user()->id)
          ->groupBy('workshift.user_id')
          ->where('workshift.user_id', Auth::user()->id)
          ->get();
      }
      return view('hrm::workshift.index', compact('workShifts', 'employees'));
    } else {
      return redirect()->back()->with('error', 'Bạn không có quyền truy cập !');
    }
  }

  /**
   * Show the form for creating a new resource.
   * @return Response
   */
  public function create()
  {
  }

  public function addEmployee(Request $request)
  {
    $data = $request->all();
    $workShifts = WorkShift::where('user_id', $data['user_id'])->first();
    if ($workShifts) {
      return redirect()->back()->with('error', 'Nhân viên đã có trong phân ca làm việc !');
    } else {
      WorkShift::create([
        'user_id' => $data['user_id'],
      ]);
      return redirect()->back()->with('success', 'Thêm nhân viên vào ca làm việc thành công !');
    }
  }

  public function store(Request $request)
  {
    $data = $request->all();
    try {
      foreach ($data['shifts'] as $userId => $shifts) {
        foreach ($shifts as $date => $shift) {
          WorkShift::updateOrCreate(
            ['date' => $date, 'user_id' => $userId],
            ['shift' => $shift]
          );
        }
      }
      return response()->json(['success' => true]);
    } catch (\Exception $e) {
      return response()->json(['success' => false, 'message' => $e->getMessage()]);
    }
  }


  public function addWorkshiftApproval(Request $request)
  {
    $existingShift = WorkShift::where([
      'user_id' => $request->user_id,
      'date' => $request->date,
      'shift' => $request->shift,
    ])->first();
    if ($existingShift) {
      return redirect()->back()->with('error', "Trùng với ca làm việc hiện tại !");
    }
    $existingWorkshift = WorkshiftApproval::where([
      'user_id' => $request->user_id,
      'date' => $request->date,
      'status' => 0,
    ])->first();

    if ($existingWorkshift) {
      return redirect()->back()->with('error', 'Đang có ca làm việc chờ phê duyệt !');
    }
    WorkshiftApproval::create([
      'user_id' => $request->user_id,
      'date' => $request->date,
      'shift' => $request->shift,
      'reason' => $request->reason,
      'status' => 0,
      'approved_by' => NULL,
    ]);

    return redirect()->back()->with('success', 'Đã gửi yêu cầu duyệt!');
  }

  public function workshiftApprovalList()
  {
    if(Auth::user()->type == 'company' || Auth::user()->type == 'hr'){
    $workshiftApprovals = WorkshiftApproval::join('users', 'users.id', '=', 'workshift_approval.user_id')
      ->select('workshift_approval.*', 'users.name as user_name')
      ->get();
    return view('hrm::workshift.approval', compact('workshiftApprovals'));
    } else {
      return redirect()->back()->with('error', 'Bạn không có quyền truy cập !');
    }
  }

  public function workshiftApproval(Request $request, string $id)
  {
    $workshiftApproval = WorkshiftApproval::find($id);
    $workshiftApproval->status = 1;
    $workshiftApproval->approved_by = Auth::user()->id;
    $workshiftApproval->save();
    WorkShift::updateOrCreate(
      [
        'date' => $workshiftApproval->date,
        'user_id' => $workshiftApproval->user_id,
        'shift' => $workshiftApproval->shift,
      ]
    );
    return redirect()->back()->with('success', 'Duyệt ca làm việc thành công !');
  }

  public function workshiftReject(Request $request, string $id)
  {
    $workshiftApproval = WorkshiftApproval::find($id);
    $workshiftApproval->status = 2;
    $workshiftApproval->approved_by = Auth::user()->id;
    $workshiftApproval->save();


    return redirect()->back()->with('success', 'Từ chối duyệt ca làm việc thành công !');
  }

 public function destroy($id)
  {
    $workShift = WorkShift::find($id);
    try{
      $workShift->delete();
    return redirect()->back()->with('success', 'Xóa ca làm việc thành công !');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Xóa ca làm việc thất bại !');
    }
  }

  public function destroyApproval($id)
  {
    $workShift = WorkshiftApproval::find($id);
    try{
      $workShift->delete();
    return redirect()->back()->with('success', 'Xóa yêu cầu duyệt ca làm việc thành công !');
    } catch (\Exception $e) {
      return redirect()->back()->with('error', 'Xóa yêu cầu duyệt ca làm việc thất bại !');
    }
  }
}
