<?php

namespace App\Http\Controllers;

use App\Models\BorrowEmployeeRecord;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Taskly\Entities\Project;

class BorrowEmployeeRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = BorrowEmployeeRecord::query()->latest()->get();
        return view('brrow-employee-records.index',compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('project manage')) {
            $objUser          = Auth::user();
                $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->projectonly()->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', getActiveWorkSpace())->where('projects.status', '!=', 'Disapproved')->get()->pluck('name', 'id');

            } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
        return view('brrow-employee-records.create',compact('projects'));

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
                    'project_id' => 'required',
                    'user_project' => 'required',
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
          $test=  BorrowEmployeeRecord::create([
                'name' => $request->input('name'),
                'user_id' => $request->input('user_project'),
                'project_id' => $request->input('project_id'),
                'borrowed_date' => $request->input('borrowed_date'),
                'borrowed_day' => $request->input('borrowed_day'),
                'give_back_day' => $request->input('give_back_day'),
                'description' => $request->input('description'),
            ]);
            return redirect()->route('borrow-employee-records.index')->with('success', __('Thêm mới văn bản thành công.'));

        }else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(BorrowEmployeeRecord $borrowEmployeeRecord)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BorrowEmployeeRecord $borrowEmployeeRecord)
    {
        $objUser          = Auth::user();
        $projects = Project::select('projects.*')->join('user_projects', 'projects.id', '=', 'user_projects.project_id')->projectonly()->where('user_projects.user_id', '=', $objUser->id)->where('projects.workspace', '=', getActiveWorkSpace())->where('projects.status', '!=', 'Disapproved')->get()->pluck('name', 'id');
       
        return view('brrow-employee-records.edit', compact('borrowEmployeeRecord','projects'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BorrowEmployeeRecord $borrowEmployeeRecord)
    {
        if (\Auth::user()->isAbleTo('proposal create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'project_id' => 'required',
                    'user_project' => 'required',
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
            $borrowEmployeeRecord->update([
                'name' => $request->input('name'),
                'user_id' => $request->input('user_project'),
                'project_id' => $request->input('project_id'),
                'borrowed_date' => $request->input('borrowed_date'),
                'borrowed_day' => $request->input('borrowed_day'),
                'give_back_day' => $request->input('give_back_day'),
                'description' => $request->input('description'),
                'status' => $request->input('status'),
            ]);
    
            return redirect()->route('borrow-employee-records.index')->with('success', __('Cập nhật văn bản thành công.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BorrowEmployeeRecord $borrowEmployeeRecord)
    {
        $borrowEmployeeRecord->delete();
        return back()->with('success', 'Record deleted successfully');
    }

    public function getUsersByProject($projectId)
    {
        $users = User::select('users.*')
            ->join('user_projects', 'users.id', '=', 'user_projects.user_id')
            ->where('user_projects.project_id', '=', $projectId)
            ->get();
        return response()->json($users);
    }
}
