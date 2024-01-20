<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Rules;

class RulesController extends Controller
{
  public function index()
  {
    $rules = Rules::all();
    return view('hrm::rules.index', compact('rules'));
  }

  public function store(Request $request)
  {
    try {
      Rules::where('id', 1)->update([
        'checkin_time' => $request->checkin_time,
        'checkout_time' => $request->checkout_time,
        'violation_handling' => $request->violation_handling,
        'attendance_calculation' => $request->attendance_calculation,
        'shift_registration' => $request->shift_registration,
      ]);
      return redirect()->route('rules.index')->with('success', 'Cập nhật thành công');
    } catch (\Exception $e) {
      return redirect()->route('rules.index')->with('error', 'Cập nhật thất bại');
    }
  }
}
