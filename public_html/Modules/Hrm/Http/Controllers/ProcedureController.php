<?php

namespace Modules\Hrm\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\ProcedureType;
use Modules\Hrm\Entities\Procedure;


class ProcedureController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
       if (Auth::user()->isAbleTo('employee manage')) {
            $procedures = Procedure::all();
            return view('hrm::procedures.index', compact('procedures'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */

    // public function show($id)
    // {
    //     if (Auth::user()->isAbleTo('employee manage')) {
    //         $procedure = Procedure::find($id);
    //         return view('hrm::procedures.show', compact('procedure'));
    //     } else {
    //         return redirect()->back()->with('error', __('Permission denied.'));
    //     }
    // }   

    public function create()
    {
        if (Auth::user()->isAbleTo('employee manage')) {
            $procedure_types = ProcedureType::all()->pluck('name', 'id');
            $procedure_types->prepend('-- Chọn loại quy trình --', '');
            return view('hrm::procedures.create', compact('procedure_types'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }  

    public function create_type()
    {
        if (Auth::user()->isAbleTo('employee manage')) {
            return view('hrm::procedures.create_type');
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    } 
    
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('employee manage')) {
            $request->validate([
                'name' => 'required',
                'procedure_type' => 'required',
                'description' => 'required',
            ]);
            $procedure = new Procedure();
            $procedure->name = $request->name;
            $procedure->procedure_type = $request->procedure_type;
            $procedure->description = $request->description;
            $procedure->save();
            return redirect()->route('procedures.index')->with('success', __('Thêm mới thành công.'));  
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function store_type(Request $request){
        if (Auth::user()->isAbleTo('employee manage')) {
            $request->validate([
                'name' => 'required',
            ]);
            $procedure_type = new ProcedureType();
            $procedure_type->name = $request->name;
            $procedure_type->save();
            return redirect()->route('procedures.index')->with('success', __('Thêm mới thành công.'));  
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit($id)
    {
        if (Auth::user()->isAbleTo('employee manage')) {
            $procedure = Procedure::find($id);
            $procedure_types = ProcedureType::all();
            return view('hrm::procedures.edit', compact('procedure', 'procedure_types'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }   

    public function update(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('employee manage')) {
            $request->validate([
                'name' => 'required',
                'procedure_type' => 'required',
            ]);
            $procedure = Procedure::find($id);
            $procedure->name = $request->name;
            $procedure->procedure_type = $request->procedure_type;
            $procedure->description = $request->description;
            $procedure->save();
            return redirect()->route('procedures.index')->with('success', __('Cập nhật thành công.'));  
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }   

    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('employee manage')) {
            $procedure = Procedure::find($id);
            $procedure->delete();
            return redirect()->route('procedures.index')->with('success', __('Xóa thành công.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

}
