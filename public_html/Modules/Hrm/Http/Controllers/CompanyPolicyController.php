<?php

namespace Modules\Hrm\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Hrm\Entities\Branch;  
use Modules\Hrm\Entities\CompanyPolicy;
use Modules\Hrm\Events\CreateCompanyPolicy;
use Modules\Hrm\Events\DestroyCompanyPolicy;
use Modules\Hrm\Events\UpdateCompanyPolicy;
use App\Models\ActivityLogCompanyPolicy;

class CompanyPolicyController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('companypolicy manage')) {
            $companyPolicy = CompanyPolicy::where('workspace', getActiveWorkSpace())->where('created_by', '=', creatorId())->with('branches')->get();
            return view('hrm::companyPolicy.index', compact('companyPolicy'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function activityLogPolicy()
    {
        if (Auth::user()->isAbleTo('companypolicy manage')) {

            $policys = ActivityLogCompanyPolicy::latest()->get();
            return view('hrm::companyPolicy.activityLog', compact('policys'));
        }

    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('companypolicy create')) {
            $branches     = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            return view('hrm::companyPolicy.create', compact('branches'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('companypolicy create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'branch' => 'required',
                    'title' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }

            if (!empty($request->attachment)) {
                $filenameWithExt = $request->file('attachment')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('attachment')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $uplaod = upload_file($request, 'attachment', $fileNameToStore, 'companyPolicy');
                if ($uplaod['flag'] == 1) {
                    $url = $uplaod['url'];
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
            }
            $policy              = new CompanyPolicy();
            $policy->branch      = $request->branch;
            $policy->title       = $request->title;
            $policy->description = !empty($request->description) ? $request->description : '';
            $policy->attachment  = !empty($request->attachment) ? $url : '';
            $policy->workspace  = getActiveWorkSpace();
            $policy->created_by  = creatorId();
            $policy->save();

            $action = 'Tạo kỷ luật';
            $typeAction = 'store';
            $branchName = Branch::find($policy->branch)->name;
            $changes = [
                'user_id'    => $request->employee_id,
                'title'      =>  $policy->title,
                'branchName' => $branchName,
                'workspace'  => getActiveWorkSpace(),
                'changed_by' => Auth::user()->name,
                'changed_at' => now()->format('H:i:s d-m-Y'),
            ];
            $this->saveLog($policy, $changes, $action,$typeAction);


            event(new CreateCompanyPolicy($request, $policy));


            return redirect()->route('company-policy.index')->with('success', __('Company policy successfully created.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return redirect()->back();
        return view('hrm::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(CompanyPolicy $companyPolicy)
    {
        if (Auth::user()->isAbleTo('companypolicy edit')) {
            $branches     = Branch::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');

            return view('hrm::companyPolicy.edit', compact('branches', 'companyPolicy'));
        } else {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, CompanyPolicy $companyPolicy)
    {
        if (Auth::user()->isAbleTo('companypolicy edit')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'branch' => 'required',
                    'title' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();
                return redirect()->back()->with('error', $messages->first());
            }
            $originalData = $companyPolicy->getOriginal();

            if (isset($request->attachment)) {
                if (!empty($companyPolicy->attachment)) {
                    delete_file($companyPolicy->attachment);
                }
                $filenameWithExt = $request->file('attachment')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('attachment')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;

                $uplaod = upload_file($request, 'attachment', $fileNameToStore, 'companyPolicy');
                if ($uplaod['flag'] == 1) {
                    $url = $uplaod['url'];
                } else {
                    return redirect()->back()->with('error', $uplaod['msg']);
                }
            }

            $companyPolicy->branch      = $request->branch;
            $companyPolicy->title       = $request->title;
            $companyPolicy->description = $request->description;
            if (isset($request->attachment)) {
                $companyPolicy->attachment = $url;
            }
            $companyPolicy->save();

            $action = 'Cập nhật kỷ luật';
            $typeAction = 'update';
            $newData = $companyPolicy->fresh()->getAttributes();
            $changes = $this->getChanges($originalData, $newData);
            $this->saveLog($companyPolicy, $changes, $action,$typeAction);

            event(new UpdateCompanyPolicy($request, $companyPolicy));
            return redirect()->route('company-policy.index')->with('success', __('Company policy successfully updated.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(CompanyPolicy $companyPolicy)
    {
        if (Auth::user()->isAbleTo('companypolicy delete')) {
            if ($companyPolicy->created_by == creatorId() && $companyPolicy->workspace == getActiveWorkSpace()) {
                if (!empty($companyPolicy->attachment)) {
                    delete_file($companyPolicy->attachment);
                }
                event(new DestroyCompanyPolicy($companyPolicy));
                $action = 'Xóa kỷ luật';
                $typeAction = 'delete';

                $title = $companyPolicy->title;
                $branch = Branch::find($companyPolicy->branch)->name;
                $changes = [
                    'title'      => $title,
                    'branch'      => $branch,
                    'changed_by' => Auth::user()->name,
                    'changed_at' => now()->format('H:i:s d-m-Y'),
                ];
                 $logData = [
                                'action_type' => $typeAction,
                                'type' => get_class($companyPolicy),
                                'log_type' => $action,
                                'remark' => json_encode(['changes' => $changes]),
                            ];
                            ActivityLogCompanyPolicy::create($logData);

                $companyPolicy->delete();
                return redirect()->route('company-policy.index')->with('success', __('Company policy successfully deleted.'));
            } else {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    private function getChanges($originalData = null, $newData = null)
    {
        $changes = [];
    
        foreach ($originalData as $key => $value) {
            if ($key === 'updated_at' || $key === 'created_at') {
                continue;
            }
    
            $oldValue = $originalData[$key];
            $newValue = $newData[$key];
    
            // Check if it's a select field
            if ($this->isSelectField($key)) {
                
                $oldName = $this->getNameFromDatabase($key, $oldValue);
                $newName = $this->getNameFromDatabase($key, $newValue);

            } else {
                $oldName = $oldValue;
                $newName = $newValue;
            }
    
            if ($oldName !== $newName) {
                $changes[$key] = [
                    'old' => $oldName,
                    'new' => $newName,
                    'changed_by' => Auth::user()->name,
                    'changed_at' => now()->format('H:i:s d-m-Y'),
                ];
            }
        }
    
        return $changes;
    }
    private function isSelectField($key)
    {
        $selectFields = ['branch'];
        return in_array($key, $selectFields);
    }

    private function getNameFromDatabase($key, $id)
    {
        switch ($key) {
            case 'branch':
                return Branch::find($id)->name;
            default:
                return $id;
        }
    }
    private function saveLog($policy, $changes, $action, $typeAction)
    {
        $logData = [
            'action_type' => $typeAction,
            'type' => get_class($policy),
            'log_type' => $action,
            'remark' => json_encode(['changes' => $changes]),
        ];
        ActivityLogCompanyPolicy::create($logData);
    }
}
