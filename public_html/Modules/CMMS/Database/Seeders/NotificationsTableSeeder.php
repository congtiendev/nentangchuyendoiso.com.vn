<?php

namespace Modules\CMMS\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\Models\Notification;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $notifications = [
            'Work Order Request','New Supplier','New POs','Work Order Assigned'
        ];
        $permissions = [
            'workorder manage',
            'suppliers manage',
            'POs purchase order manage',
            'workorder manage',
        ];

            foreach($notifications as $key=>$n){
                $ntfy = Notification::where('action',$n)->where('type','mail')->where('module','CMMS')->count();
                if($ntfy == 0){
                    $new = new Notification();
                    $new->action = $n;
                    $new->status = 'on';
                    $new->permissions = $permissions[$key];
                    $new->module = 'CMMS';
                    $new->type = 'mail';
                    $new->save();
                }
            }
    }
}
