<?php

namespace App\Http\Controllers\Auth;

use App\Events\DefaultData;
use App\Events\GivePermissionToRole;
use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use App\Models\WorkSpace;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public $admin_settings;

    public function setting(){
        $this->admin_settings = getAdminAllSetting();

    }
    public function __construct()
    {
        $this->setting();

        if(!file_exists(storage_path() . "/installed"))
        {
            header('location:install');
            die;
        }
        if(module_is_active('GoogleCaptcha') && (isset($this->admin_settings['google_recaptcha_is_on']) ? $this->admin_settings['google_recaptcha_is_on'] : 'off') == 'on' )
        {
            config(['captcha.secret' => isset($this->admin_settings['google_recaptcha_secret']) ? $this->admin_settings['google_recaptcha_secret'] : '']);
            config(['captcha.sitekey' => isset($this->admin_settings['google_recaptcha_key']) ? $this->admin_settings['google_recaptcha_key'] : '']);
        }
        // $this->middleware('guest')->except('logout');
    }
    public function create($lang = '')
    {
        if (empty( $this->admin_settings['signup']) ||  (isset($this->admin_settings['signup']) ? $this->admin_settings['signup'] : 'off') == "on")
        {
            if($lang == '')
            {
                $lang = getActiveLanguage();
            }
            else
            {
                $lang = array_key_exists($lang, languages()) ? $lang : 'en';
            }
            \App::setLocale($lang);
            return view('auth.register',compact('lang'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'store_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);
        if(module_is_active('GoogleCaptcha') && admin_setting('google_recaptcha_is_on') == 'on' )
        {
            $request->validate([
                'g-recaptcha-response' => 'required|captcha',
            ]);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Auth::login($user);

        $role_r = Role::where('name','company')->first();
        if(!empty($user))
        {
            $user->addRole($role_r);
            // WorkSpace slug create on WorkSpace Model
            $workspace = new WorkSpace();
            $workspace->name = $request->store_name;
            $workspace->created_by = $user->id;
            $workspace->save();

            $user_work = User::find($user->id);
            $user_work->active_workspace = $workspace->id;
            $user_work->workspace_id = $workspace->id;
            $user_work->save();

            User::CompanySetting($user->id);
            $uArr = [
                'email'=> $request->email,
                'password'=> $request->password,
                'company_name'=>$request->name,
            ];
            $data= $user->MakeRole();
            $plan = Plan::where('is_free_plan',1)->first();
            if($plan)
            {
                $user->assignPlan($plan->id,'Month',$plan->modules,0,$user->id);
            }
            // custom event for role
            $client_id =$data['client_role']->id;
            $staff_role =$data['staff_role']->id;

            if(!empty($user->active_module))
            {
                event(new GivePermissionToRole($client_id,'client',$user->active_module));
                event(new GivePermissionToRole($staff_role,'staff',$user->active_module));
                event(new DefaultData($user->id,$workspace->id,$user->active_module));
            }

            if(!empty($request->type) && $request->type != "pricing")
            {
                $plan = Plan::where('is_free_plan',1)->first();
                if($plan)
                {
                    $user->assignPlan($plan->id,'Month',$plan->modules,0,$user->id);
                }
            }
            if ( admin_setting('email_verification') == 'on')
            {
                try
                {
                    $admin_user = User::where('type','super admin')->first();
                    SetConfigEmail(!empty($admin_user->id) ? $admin_user->id : null);
                    $resp = EmailTemplate::sendEmailTemplate('New User', [$user->email], $uArr,$admin_user->id);
                    event(new Registered($user));
                }
                catch(\Exception $e)
                {
                    $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                }
            }
            else
            {
                $user_work = User::find($user->id);
                $user_work->email_verified_at = date('Y-m-d h:i:s');
                $user_work->save();
            }

        }

        return redirect('plans');
        // return redirect(RouteServiceProvider::HOME);
    }
}
