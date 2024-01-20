<?php

namespace Modules\CMMS\Database\Seeders;


use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Artisan;
use App\Models\Role;
use App\Models\Permission;


class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        Artisan::call('cache:clear');

        $permission  = [
            'cmms manage',
            'cmms dashboard manage',
            'location manage',
            'location create',
            'location edit',
            'location delete',
            'components manage',
            'components create',
            'components edit',
            'components delete',
            'components show',
            'components associate',
            'workorder manage',
            'workorder create',
            'workorder edit',
            'workorder delete',
            'workorder import',
            'workorder show',
            'workorder associate',
            'parts manage',
            'parts create',
            'parts edit',
            'parts delete',
            'parts show',
            'parts associate',
            'pms manage',
            'pms create',
            'pms edit',
            'pms delete',
            'pms show',
            'pms associate',
            'suppliers manage',
            'suppliers create',
            'suppliers edit',
            'suppliers delete',
            'suppliers show',
            'suppliers associate',
            'logtime create',
            'logtime delete',
            'logtime edit',
            'POs purchase order manage',
            'POs purchase order create',
            'POs purchase order edit',
            'POs purchase order delete',
            'POs purchase order associate',
        ];

        $company_role = Role::where('name','company')->first();
        foreach ($permission as $key => $value)
        {
            $table = Permission::where('name',$value)->where('module','CMMS')->exists();
            if(!$table)
            {
                $permission = Permission::create(
                    [
                        'name' => $value,
                        'guard_name' => 'web',
                        'module' => 'CMMS',
                        'created_by' => 0,
                        "created_at" => date('Y-m-d H:i:s'),
                        "updated_at" => date('Y-m-d H:i:s')
                    ]
                );
                if(!$company_role->hasPermission($value))
                {
                    $company_role->givePermission($permission);
                }
            }
        }
    }
}
