<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Employee;
use App\Models\User;
use App\Models\WorkshiftTypes;
use Carbon\Carbon;
use DB;

class WorkShiftTypesController  extends Controller
{
    public function index()
    {
        $workshiftTypes = WorkshiftTypes::all();
        return view('hrm::workshift_types.index', compact('workshiftTypes'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('attendance manage')) {
            $data = $request->all();
            $workshiftTypes = WorkshiftTypes::all();
            //kiem tra xem co ca lam viec nao co thoi gian trung voi ca lam viec moi them vao hay khong
            if ($data['start_time'] == $data['end_time']) {
                return redirect()->back()->with('error', 'Thời gian bắt đầu và kết thúc không được trùng nhau !');
            } else if ($data['start_time'] > $data['end_time']) {
                return redirect()->back()->with('error', 'Thời gian bắt đầu không được lớn hơn thời gian kết thúc !');
            } else {
                foreach ($workshiftTypes as $workshiftType) {
                    if ($data['start_time'] >= $workshiftType->start_time && $data['start_time'] <= $workshiftType->end_time) {
                        return redirect()->back()->with('error', 'Thời gian bắt đầu đã bị trùng với ca làm việc ' . $workshiftType->name . ' !');
                    } else if ($data['end_time'] >= $workshiftType->start_time && $data['end_time'] <= $workshiftType->end_time) {
                        return redirect()->back()->with('error', 'Thời gian kết thúc đã bị trùng với ca làm việc ' . $workshiftType->name . ' !');
                    }
                }
            }
            try {
                WorkshiftTypes::create([
                    'name' => $data['name'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                ]);
                return redirect()->back()->with('success', 'Thêm mới loại ca thành công !');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Thêm mới loại ca thất bại ! Lỗi: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập !');
        }
    }

    public function update(string $id, Request $request)
    {
        if (Auth::user()->isAbleTo('attendance manage')) {
            $data = $request->all();
            $workshiftTypes = WorkshiftTypes::all();
            if ($data['start_time'] == $data['end_time']) {
                return redirect()->back()->with('error', 'Thời gian bắt đầu và kết thúc không được trùng nhau !');
            } else if ($data['start_time'] > $data['end_time']) {
                return redirect()->back()->with('error', 'Thời gian bắt đầu không được lớn hơn thời gian kết thúc !');
            }
            try {
                WorkshiftTypes::where('id', $id)->update([
                    'name' => $data['name'],
                    'start_time' => $data['start_time'],
                    'end_time' => $data['end_time'],
                ]);
                return redirect()->back()->with('success', 'Cập nhật loại ca thành công !');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Cập nhật loại ca thất bại ! Lỗi: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập !');
        }
    }

    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('attendance manage')) {
            try {
                WorkshiftTypes::where('id', $id)->delete();
                return redirect()->back()->with('success', 'Xóa loại ca thành công !');
            } catch (\Exception $e) {
                return redirect()->back()->with('error', 'Xóa loại ca thất bại ! Lỗi: ' . $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', 'Bạn không có quyền truy cập !');
        }
    }
}
