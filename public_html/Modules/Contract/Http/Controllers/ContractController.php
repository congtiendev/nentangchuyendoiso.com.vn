<?php

namespace Modules\Contract\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use App\Models\EmailTemplate;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Modules\Contract\Entities\Contract;
use Modules\Contract\Entities\ContractType;
use Modules\Contract\Entities\ContractAttechment;
use Modules\Contract\Entities\ContractComment;
use Modules\Contract\Entities\ContractNote;
use Illuminate\Support\Facades\Validator;
use Modules\Contract\Events\CopyContract;
use Modules\Contract\Events\CreateContract;
use Modules\Contract\Events\DestroyContract;
use Modules\Contract\Events\SendMailContract;
use Modules\Contract\Events\StatusChangeContract;
use Modules\Contract\Events\UpdateContract;
use App\Models\CustomNotification;
use App\Models\UserNotifications;
use App\Models\ContractSample;
use PhpParser\Node\Expr\FuncCall;

class ContractController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        if (Auth::user()->isAbleTo('contract manage')) {
            if (Auth::user()->type == 'company') {
                $contracts = Contract::select('contracts.*', 'contract_types.name as contract_type', 'users.name as user_name', 'projects.name as project_name')->leftJoin('contract_types', 'contracts.type', '=', 'contract_types.id')->leftJoin('users', 'contracts.user_id', '=', 'users.id')->leftJoin('projects', 'contracts.project_id', '=', 'projects.id')->where('contracts.created_by', '=', creatorId())->where('contracts.status','!=','liquidation')->where('contracts.workspace', getActiveWorkSpace())->get();

                $curr_month  = Contract::where('created_by', '=', creatorId())->whereMonth('start_date', '=', date('m'))->get();
                $curr_week   = Contract::where('created_by', '=', creatorId())->whereBetween(
                    'start_date',
                    [
                        \Carbon\Carbon::now()->startOfWeek(),
                        \Carbon\Carbon::now()->endOfWeek(),
                    ]
                )->get();
                $last_30days = Contract::where('created_by', '=', creatorId())->whereDate('start_date', '>', \Carbon\Carbon::now()->subDays(30))->get();

                // Contracts Summary
                $cnt_contract                = [];
                $cnt_contract['total']       = Contract::getContractSummary($contracts);
                $cnt_contract['this_month']  = Contract::getContractSummary($curr_month);
                $cnt_contract['this_week']   = Contract::getContractSummary($curr_week);
                $cnt_contract['last_30days'] = Contract::getContractSummary($last_30days);

                return view('contract::contracts.index', compact('contracts', 'cnt_contract'));
            } else {
                $contracts   = Contract::where('user_id', '=', Auth::user()->id)->where('status','!=','liquidation')->get();
                $curr_month  = Contract::where('user_id', '=', Auth::user()->id)->whereMonth('start_date', '=', date('m'))->get();
                $curr_week   = Contract::where('user_id', '=', Auth::user()->id)->whereBetween(
                    'start_date',
                    [
                        \Carbon\Carbon::now()->startOfWeek(),
                        \Carbon\Carbon::now()->endOfWeek(),
                    ]
                )->get();
                $last_30days = Contract::where('user_id', '=', Auth::user()->id)->whereDate('start_date', '>', \Carbon\Carbon::now()->subDays(30))->get();

                // Contracts Summary
                $cnt_contract                = [];
                $cnt_contract['total']       = Contract::getContractSummary($contracts);
                $cnt_contract['this_month']  = Contract::getContractSummary($curr_month);
                $cnt_contract['this_week']   = Contract::getContractSummary($curr_week);
                $cnt_contract['last_30days'] = Contract::getContractSummary($last_30days);

                return view('contract::contracts.index', compact('contracts', 'cnt_contract'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function accept(string $id){
        try{
            $contract = Contract::findOrFail($id);
            $contract->status = 'accept';
            $contract->save();
            return redirect()->back()->with('success', "Hợp đồng đã được chấp nhận");
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function decline(string $id){
        try{
            $contract = Contract::findOrFail($id);
            $contract->status = 'decline';
            $contract->save();
            return redirect()->back()->with('success', "Hợp đồng đã bị từ chối");
        }catch(\Exception $e){
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function samples()
    {
        if (Auth::user()->isAbleTo('contract manage')) {
            $contracts = ContractSample::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
            return view('contract::contract_sample.index', compact('contracts'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function samplesStore(Request $request)
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
        }else{
            return redirect()->back()->with('error', __('Please upload file'));
        }
        $data = [
            'name' => $request->name,
            'content' => $uploadContent['url'],
            'contract_object' => $request->contract_object,
            'competent_person' => $request->competent_person,
            'description' => $request->description,
            'created_by' => Auth::user()->id,
            'contract_type' => $request->contract_type,
            'workspace' => getActiveWorkSpace(),
        ];
        try {
            ContractSample::create($data);
            return redirect()->back()->with('success', __('Contract successfully created!'));
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function detailSampleStore(string $id, Request $request)
    {
        $contract  = ContractSample::findOrFail($id);
        $contract_type = ContractType::where('id', $contract->contract_type)->first();
        $dark_logo    = get_file(sidebar_logo());
        $img = (!empty($dark_logo) ? $dark_logo : get_file('uploads/logo/logo_dark.png'));
        $company_id = $contract->created_by;
        $workspace_id = $contract->workspace;
        return view('contract::contract_sample.detail', compact('contract', 'contract_type', 'img', 'company_id', 'workspace_id'));
    }

    public function destroySample($id)
    {
        $contract = ContractSample::find($id);
        if ($contract->created_by == creatorId() && $contract->workspace == getActiveWorkSpace()) {
            $contract->delete();
            return redirect()->back()->with('success', __('Contract successfully deleted!'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function updateSample($id, Request $request)
    {
        $contract = ContractSample::find($id);
        if ($contract->created_by == creatorId() && $contract->workspace == getActiveWorkSpace()) {
            $data = [
                'name' => $request->name,
                'contract_object' => $request->contract_object,
                'competent_person' => $request->competent_person,
                'description' => $request->description,
                'contract_type' => $request->contract_type,
            ];
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
                $data['content'] = $uploadContent['url'];
            }
            try {
                $contract->update($data);
                return redirect()->back()->with('success', __('Contract successfully updated!'));
            } catch (\Exception $e) {
                dd($e->getMessage());
                return redirect()->back()->with('error', $e->getMessage());
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }



    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        if (Auth::user()->isAbleTo('contract create')) {
            $user       = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->get()->pluck('name', 'id');
            $contractType = ContractType::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            if (module_is_active('CustomField')) {
                $customFields =  \Modules\CustomField\Entities\CustomField::where('workspace_id', getActiveWorkSpace())->where('module', '=', 'contract')->where('sub_module', 'contract')->get();
            } else {
                $customFields = null;
            }
            return view('contract::contracts.create', compact('contractType', 'user', 'customFields'));
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        if (Auth::user()->isAbleTo('contract create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'subject' => 'required',
                    'value' => 'required',
                    'type' => 'required',
                    'user_id' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('contract.index')->with('error', $messages->first());
            }

            $contract              = new Contract();
            $contract->subject     = $request->subject;
            $contract->user_id     = (Auth::user()->type == 'company') ? $request->user_id : Auth::user()->id;
            $contract->project_id  = $request->project_id;
            $contract->value       = $request->value;
            $contract->type        = $request->type;
            $contract->start_date  = $request->start_date;
            $contract->end_date    = $request->end_date;
            $contract->notes       = $request->notes;
            $contract->workspace   = getActiveWorkSpace();
            $contract->created_by  = creatorId();
            $contract->save();


            if (module_is_active('CustomField')) {
                \Modules\CustomField\Entities\CustomField::saveData($contract, $request->customField);
            }

            try {
                $notification  = CustomNotification::create([
                    'title' => 'Hợp đồng mới',
                    'content' => 'đã thêm bạn vào hợp đồng ' . $contract->subject,
                    'link' => route('contract.show', $contract->id),
                    'from' => $contract->created_by,
                    'send_to' => json_encode([$contract->user_id], JSON_NUMERIC_CHECK),
                    'type' => 'new_contract',
                ]);
                UserNotifications::create([
                    'user_id' => $contract->user_id,
                    'notification_id' => $notification->id,
                    'is_read' => 0
                ]);
            } catch (\Exception $e) {
                return redirect()->back()->with('error', __('Permission Denied : ') . $e->getMessage());
            }

            event(new CreateContract($request, $contract));

            return redirect()->back()->with('success', __('Contract successfully created!'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function getProject(Request $request)
    {
        if ($request->user_id == 0) {
            $project = \Modules\Taskly\Entities\ClientProject::get()->pluck('name', 'id')->toArray();
        } else {
            $projectss = \Modules\Taskly\Entities\ClientProject::where('client_id', $request->user_id)->get()->pluck('project_id');
            $project  = \Modules\Taskly\Entities\Project::whereIn('id', $projectss)->projectonly()->get()->pluck('name', 'id');
        }

        return response()->json($project);
    }

    public static function contractNumber()
    {
        $latest = Contract::where('created_by', '=', creatorId())->latest()->first();
        if (!$latest) {
            return 1;
        }

        return $latest->id + 1;
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $contract = Contract::select('contracts.*', 'contract_types.name as contract_type', 'users.name as user_name', 'projects.name as project_name')->leftJoin('contract_types', 'contracts.type', '=', 'contract_types.id')->leftJoin('users', 'contracts.user_id', '=', 'users.id')->leftJoin('projects', 'contracts.project_id', '=', 'projects.id')->where('contracts.id', '=', $id)->first();

        if ($contract) {
            if ($contract->created_by == creatorId() && $contract->workspace == getActiveWorkSpace()) {
                $client   = $contract->client;
                $comments = ContractComment::where('contract_id', $contract->id)->get();
                $notes = ContractNote::where('contract_id', $contract->id)->get();
                $files = ContractAttechment::where('contract_id', $contract->id)->get();
                return view('contract::contracts.show', compact('contract', 'client', 'comments', 'notes', 'files'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Contract Note Found.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(Contract $contract)
    {
        if (Auth::user()->isAbleTo('contract edit')) {
            if ($contract->created_by == creatorId() && $contract->workspace == getActiveWorkSpace()) {
                $user       = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->get()->pluck('name', 'id');
                $contractType = ContractType::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
                $project  = \Modules\Taskly\Entities\Project::where('id', $contract->project_id)->where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->projectonly()->get()->pluck('name', 'id');

                if (module_is_active('CustomField')) {
                    $contract->customField = \Modules\CustomField\Entities\CustomField::getData($contract, 'contract', 'contract');
                    $customFields             = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'contract')->where('sub_module', 'contract')->get();
                } else {
                    $customFields = null;
                }

                return view('contract::contracts.edit', compact('contract', 'contractType', 'user', 'customFields', 'project'));
            } else {
                return response()->json(['error' => __('Permission Denied.')], 401);
            }
        } else {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, Contract $contract)
    {
        if (Auth::user()->isAbleTo('contract edit')) {
            if ($contract->created_by == creatorId() && $contract->workspace == getActiveWorkSpace()) {
                $validator = \Validator::make(
                    $request->all(),
                    [
                        'subject' => 'required',
                        'value' => 'required',
                        'type' => 'required',
                        'user_id' => 'required',
                        'start_date' => 'required',
                        'end_date' => 'required',
                    ]
                );

                if ($validator->fails()) {
                    $messages = $validator->getMessageBag();

                    return redirect()->route('contract.index')->with('error', $messages->first());
                }
                $notification_content = array();
                $send_to = [$request->user_id];
                if ($contract->user_id != $request->user_id) {
                    $contract->user = User::find($contract->user_id);
                    $newUser = User::find($request->user_id);
                    $notification_content[] = "Đã thay đổi người phụ trách từ " . $contract->user->name . " sang " . $newUser->name;
                    $send_to[] = $contract->user_id;
                }
                if ($contract->project_id != $request->project_id) {
                    $contract->project = \Modules\Taskly\Entities\Project::find($contract->project_id);
                    $newProject = \Modules\Taskly\Entities\Project::find($request->project_id);
                    $notification_content[] = "Đã thay đổi dự án từ " . " sang " . $newProject->name;
                }
                //$contract->project->name. lấy ra tạm thời
                if ($contract->subject != $request->subject) {
                    $notification_content[] = "Đã thay đổi tiêu đề từ " . $contract->subject . " sang " . $request->subject;
                }

                if ($contract->value != $request->value) {
                    $notification_content[] = "Đã thay đổi giá trị từ " . $contract->value . " sang " . $request->value;
                }

                if ($contract->type != $request->type) {
                    $contract->contract_type = ContractType::find($contract->type);
                    $newContractType = ContractType::find($request->type);
                    $notification_content[] = "Đã thay đổi loại hợp đồng từ " . $contract->contract_type->name . " sang " . $newContractType->name;
                }

                if ($contract->start_date != $request->start_date) {
                    $notification_content[] = "Đã thay đổi ngày bắt đầu từ " . $contract->start_date . " sang " . $request->start_date;
                }

                if ($contract->end_date != $request->end_date) {
                    $notification_content[] = "Đã thay đổi ngày kết thúc từ " . $contract->end_date . " sang " . $request->end_date;
                }

                if ($contract->notes != $request->notes) {
                    $notification_content[] = "Đã thay đổi ghi chú từ " . $contract->notes . " sang " . $request->notes;
                }

                foreach ($notification_content as $value) {
                    try {
                        $notification = CustomNotification::create([
                            'title' => 'Hợp đồng đã thay đổi',
                            'content' => $value,
                            'link' => route('contract.show', $contract->id),
                            'from' => $contract->created_by,
                            'send_to' => json_encode($send_to, JSON_NUMERIC_CHECK),
                            'type' => 'update_contract',
                        ]);
                        foreach ($send_to as $user_id) {
                            UserNotifications::create([
                                'user_id' => $user_id,
                                'notification_id' => $notification->id,
                                'is_read' => 0
                            ]);
                        }
                    } catch (\Exception $e) {
                        return redirect()->back()->with('error', __('Permission Denied : ') . $e->getMessage());
                    }
                }
                $contract->user_id     = $request->user_id;
                $contract->project_id  = $request->project_id;
                $contract->subject     = $request->subject;
                $contract->value       = $request->value;
                $contract->type        = $request->type;
                $contract->start_date  = $request->start_date;
                $contract->end_date    = $request->end_date;
                $contract->notes       = $request->notes;
                $contract->save();

                if (module_is_active('CustomField')) {
                    \Modules\CustomField\Entities\CustomField::saveData($contract, $request->customField);
                }
                event(new UpdateContract($request, $contract));

                return redirect()->back()->with('success', __('Contract successfully updated!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function contract_status_edit(Request $request, $id)
    {
        $contract = Contract::find($id);
        $contract->status   = $request->status;
        $contract->save();
        event(new StatusChangeContract($request, $contract));
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        if (Auth::user()->isAbleTo('contract delete')) {
            $contract = Contract::find($id);
            if ($contract->created_by == creatorId() && $contract->workspace == getActiveWorkSpace()) {
                event(new DestroyContract($contract));

                // $attechments = $contract->ContractAttechment()->get()->each;

                // foreach($attechments->items as $attechment){
                //     delete_file($attechment->files);
                //     $attechment->delete();
                // }

                // $contract->ContractComment()->get()->each->delete();
                // $contract->ContractNote()->get()->each->delete();
                $contract->delete();
                if (module_is_active('CustomField')) {
                    $customFields = \Modules\CustomField\Entities\CustomField::where('module', 'contract')->where('sub_module', 'contract')->get();
                    foreach ($customFields as $customField) {
                        $value = \Modules\CustomField\Entities\CustomFieldValue::where('record_id', '=', $id)->where('field_id', $customField->id)->first();
                        if (!empty($value)) {
                            $value->delete();
                        }
                    }
                }

                return redirect()->back()->with('success', __('Contract successfully deleted!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function descriptionStore($id, Request $request)
    {
        if (Auth::user()->type == 'company') {
            $contract        = Contract::find($id);
            $contract->description = $request->description;
            $contract->save();
            return redirect()->back()->with('success', __('Descripation successfully saved.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    public function fileUpload($id, Request $request)
    {
        $contract = Contract::find($id);
        if ($contract->created_by == creatorId() && $contract->workspace == getActiveWorkSpace()) {
            $request->validate(['file' => 'required']);
            $files = $request->file->getClientOriginalName();
            $path = upload_file($request, 'file', $files, 'contract_file');
            if ($path['flag'] == 1) {
                $file = $path['url'];
            } else {
                return redirect()->back()->with('error', __($path['msg']));
            }
            $file                 = ContractAttechment::create(
                [
                    'contract_id' => $request->contract_id,
                    'user_id' => Auth::user()->id,
                    'workspace' => getActiveWorkSpace(),
                    'files' => $file,
                ]
            );
            $return               = [];
            $return['is_success'] = true;
            $return['download']   = route(
                'contracts.file.download',
                [
                    $contract->id,
                    $file->id,
                ]
            );
            $return['delete']     = route(
                'contracts.file.delete',
                [
                    $contract->id,
                    $file->id,
                ]
            );

            return response()->json(
                [
                    'is_success' => true,
                    'success' => __('Status successfully updated!'),
                ],
                200
            );
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('Permission Denied.'),
                ],
                401
            );
        }
    }

    public function fileDownload($id, $file_id)
    {
        $contract = Contract::find($id);
        if ($contract->created_by == creatorId() && $contract->workspace == getActiveWorkSpace()) {
            $file = ContractAttechment::find($file_id);
            if ($file) {
                $file_path = get_base_file($file->files);

                // $files = $file->files;
                return \Response::download(
                    $file_path,
                    $file->files,
                    [
                        'Content-Length: ' . get_size($file_path),
                    ]
                );
            } else {
                return redirect()->back()->with('error', __('File is not exist.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function fileDelete($id, $file_id)
    {

        $contract = Contract::find($id);
        $file = ContractAttechment::find($file_id);
        if ($file) {
            $path = get_base_file($file->files);
            if (file_exists($path)) {
                \File::delete($path);
            }
            $file->delete();

            return redirect()->back()->with('success', __('Attechment successfully delete.'));
        } else {
            return response()->json(
                [
                    'is_success' => false,
                    'error' => __('File is not exist.'),
                ],
                200
            );
        }
    }

    public function commentStore(Request $request, $id)
    {
        if (Auth::user()->isAbleTo('comment create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'comment' => 'required',
                ]
            );
            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $contract              = new ContractComment();
            $contract->comment     = $request->comment;
            $contract->contract_id = $id;
            $contract->workspace = getActiveWorkSpace();
            $contract->user_id     = Auth::user()->id;
            $contract->save();


            return redirect()->back()->with('success', __('comments successfully created!') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''))->with('status', 'comments');
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function commentDestroy($id)
    {
        if (\Auth::user()->isAbleTo('comment delete')) {
            $contract = ContractComment::find($id);
            $contract->delete();

            return redirect()->back()->with('success', __('Comment successfully deleted!'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function noteStore($id, Request $request)
    {
        if (\Auth::user()->isAbleTo('contract note create')) {
            $contract              = Contract::find($id);
            $notes                 = new ContractNote();
            $notes->contract_id    = $contract->id;
            $notes->note           = $request->note;
            $notes->user_id        = Auth::user()->id;
            $notes['workspace'] = getActiveWorkSpace();
            $notes->created_by     = creatorId();
            $notes->save();
            return redirect()->back()->with('success', __('Note successfully saved.'));
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }


    public function noteDestroy($id)
    {
        if (\Auth::user()->isAbleTo('contract note delete')) {
            $contract = ContractNote::find($id);
            if ($contract->created_by == creatorId() && $contract->workspace == getActiveWorkSpace()) {
                $contract->delete();

                return redirect()->back()->with('success', __('Note successfully deleted!'));
            } else {
                return redirect()->back()->with('error', __('Permission Denied.'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission denied'));
        }
    }

    public function copycontract($id)
    {
        if (\Auth::user()->isAbleTo('contract create')) {
            $user       = User::where('workspace_id', getActiveWorkSpace())->where('created_by', '=', creatorId())->get()->pluck('name', 'id');
            $contract = Contract::find($id);
            $contractType = ContractType::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get()->pluck('name', 'id');
            if (module_is_active('CustomField')) {
                $contract->customField = \Modules\CustomField\Entities\CustomField::getData($contract, 'contract', 'contract');
                $customFields          = \Modules\CustomField\Entities\CustomField::where('workspace_id', '=', getActiveWorkSpace())->where('module', '=', 'contract')->where('sub_module', 'contract')->get();
            } else {
                $customFields = null;
            }
            return view('contract::contracts.copy', compact('contract', 'contractType', 'user', 'customFields'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    public function copycontractstore(Request $request)
    {
        if (\Auth::user()->isAbleTo('contract create')) {
            $validator = \Validator::make(
                $request->all(),
                [
                    'subject' => 'required',
                    'value' => 'required',
                    'type' => 'required',
                    'user_id' => 'required',
                    'start_date' => 'required',
                    'end_date' => 'required',
                ]
            );

            if ($validator->fails()) {
                $messages = $validator->getMessageBag();

                return redirect()->route('contract.index')->with('error', $messages->first());
            }

            $contract              = new Contract();
            $contract->subject     = $request->subject;
            $contract->user_id     = $request->user_id;
            $contract->project_id  = $request->project_id;
            $contract->value       = $request->value;
            $contract->type        = $request->type;
            $contract->start_date  = $request->start_date;
            $contract->end_date    = $request->end_date;
            $contract->notes       = $request->notes;
            $contract->workspace   = getActiveWorkSpace();
            $contract->created_by  = creatorId();
            $contract->save();

            if (module_is_active('CustomField')) {
                \Modules\CustomField\Entities\CustomField::saveData($contract, $request->customField);
            }

            event(new CopyContract($request, $contract));

            return redirect()->route('contract.index')->with('success', __('Contract successfully created!'));
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function printContract($id)
    {
        $contract  = Contract::findOrFail($id);
        $contract_type = ContractType::where('id', $contract->type)->first();

        //Set your logo

        $dark_logo    = get_file(sidebar_logo());
        $img = (!empty($dark_logo) ? $dark_logo : get_file('uploads/logo/logo_dark.png'));
        $company_id = $contract->created_by;
        $workspace_id = $contract->workspace;
        return view('contract::contracts.contract_view', compact('contract', 'contract_type', 'img', 'company_id', 'workspace_id'));
    }


    public function pdffromcontract($contract_id)
    {
        $id = \Illuminate\Support\Facades\Crypt::decrypt($contract_id);

        $contract  = Contract::findOrFail($id);
        $contract_type = ContractType::where('id', $contract->id)->first();
        if (Auth::check()) {
            $usr = Auth::user();
        } else {
            $usr = User::where('id', $contract->created_by)->first();
        }
        $dark_logo    = get_file(sidebar_logo());
        $img = (!empty($dark_logo) ? $dark_logo : get_file('uploads/logo/logo_dark.png'));
        $company_id = $contract->created_by;
        $workspace_id = $contract->workspace;
        return view('contract::contracts.template', compact('contract', 'usr', 'contract_type', 'img', 'company_id', 'workspace_id'));
    }

    public function signature($id)
    {
        $contract = Contract::find($id);
        return view('contract::contracts.signature', compact('contract'));
    }


    public function signatureStore(Request $request)
    {
        $contract              = Contract::find($request->contract_id);
        if (Auth::user()->type == 'company') {
            $contract->owner_signature       = $request->owner_signature;
        } else {

            $contract->client_signature       = $request->client_signature;
        }

        $contract->save();

        return response()->json(
            [
                'Success' => true,
                'message' => __('Contract Signed successfully'),
            ],
            200
        );
    }

    public function sendmailContract($id, Request $request)
    {

        if (Auth::user()->type == 'company') {
            $company_settings = getCompanyAllSetting();
            if (!empty($company_settings['Contract']) && $company_settings['Contract']  == true) {
                $contract              = Contract::find($id);
                $contractArr = [
                    'contract_id' => $contract->id,
                ];
                $client = User::find($contract->user_id);
                $estArr = [
                    'email' => $client->email,
                    'contract_subject' => $contract->subject,
                    'contract_client' => $client->name,
                    'contract_start_date' => $contract->start_date,
                    'contract_end_date' => $contract->end_date,
                ];
                // Send Email
                $resp = EmailTemplate::sendEmailTemplate('Contract', [$client->id => $client->email], $estArr);

                event(new SendMailContract($request, $contract));

                return redirect()->back()->with('success', __('Mail Send successfully!') . ((isset($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
            } else {

                return redirect()->back()->with('error', __('Contract notification is off'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }



    public function setting(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'contract_prefix' => 'required',
            ]
        );
        if ($validator->fails()) {
            $messages = $validator->getMessageBag();
            return redirect()->back()->with('error', $messages->first());
        } else {
            $contract = [];
            $contract['contract_prefix'] =  $request->contract_prefix;
            foreach ($contract as $key => $value) {
                // Define the data to be updated or inserted
                $data = [
                    'key' => $key,
                    'workspace' => getActiveWorkSpace(),
                    'created_by' => creatorId(),
                ];

                // Check if the record exists, and update or insert accordingly
                Setting::updateOrInsert($data, ['value' => $value]);
            }
            // Settings Cache forget
            comapnySettingCacheForget();
            return redirect()->back()->with('success', __('Contract Setting save successfully'));
        }
    }

    public function grid()
    {
        if (\Auth::user()->isAbleTo('contract manage')) {
            if (Auth::user()->type == 'company') {
                $contracts   = Contract::where('created_by', '=', creatorId())->where('workspace', getActiveWorkSpace())->get();
                $curr_month  = Contract::where('created_by', '=', creatorId())->whereMonth('start_date', '=', date('m'))->get();
                $curr_week   = Contract::where('created_by', '=', creatorId())->whereBetween(
                    'start_date',
                    [
                        \Carbon\Carbon::now()->startOfWeek(),
                        \Carbon\Carbon::now()->endOfWeek(),
                    ]
                )->get();
                $last_30days = Contract::where('created_by', '=', creatorId())->whereDate('start_date', '>', \Carbon\Carbon::now()->subDays(30))->get();

                // Contracts Summary
                $cnt_contract                = [];
                $cnt_contract['total']       = Contract::getContractSummary($contracts);
                $cnt_contract['this_month']  = Contract::getContractSummary($curr_month);
                $cnt_contract['this_week']   = Contract::getContractSummary($curr_week);
                $cnt_contract['last_30days'] = Contract::getContractSummary($last_30days);

                return view('contract::contracts.grid', compact('contracts', 'cnt_contract'));
            } else {
                $contracts   = Contract::where('user_id', '=', Auth::user()->id)->get();
                $curr_month  = Contract::where('user_id', '=', Auth::user()->id)->whereMonth('start_date', '=', date('m'))->get();
                $curr_week   = Contract::where('user_id', '=', Auth::user()->id)->whereBetween(
                    'start_date',
                    [
                        \Carbon\Carbon::now()->startOfWeek(),
                        \Carbon\Carbon::now()->endOfWeek(),
                    ]
                )->get();
                $last_30days = Contract::where('user_id', '=', Auth::user()->id)->whereDate('start_date', '>', \Carbon\Carbon::now()->subDays(30))->get();

                // Contracts Summary
                $cnt_contract                = [];
                $cnt_contract['total']       = Contract::getContractSummary($contracts);
                $cnt_contract['this_month']  = Contract::getContractSummary($curr_month);
                $cnt_contract['this_week']   = Contract::getContractSummary($curr_week);
                $cnt_contract['last_30days'] = Contract::getContractSummary($last_30days);

                return view('contract::contracts.grid', compact('contracts', 'cnt_contract'));
            }
        } else {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function liquidation($id){
        $contract = Contract::find($id);
        $contract->status = 'liquidation';
        $contract->save();
        return redirect()->back()->with('success', __('Contract status successfully updated!'));
    }

    public function list_liquidation(){
        $contracts = Contract::select('contracts.*', 'contract_types.name as contract_type', 'users.name as user_name', 'projects.name as project_name')->leftJoin('contract_types', 'contracts.type', '=', 'contract_types.id')->leftJoin('users', 'contracts.user_id', '=', 'users.id')->leftJoin('projects', 'contracts.project_id', '=', 'projects.id')->where('contracts.created_by', '=', creatorId())->where('contracts.workspace', getActiveWorkSpace())->where('contracts.status','liquidation')->get();
        return view('contract::contracts.liquidation', compact('contracts'));
    }
}
