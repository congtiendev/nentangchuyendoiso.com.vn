<?php

namespace Modules\CMMS\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Workorder extends Model
{
    use HasFactory;
    protected $fillable = [
        'wo_id',
        'components_id',
        'user_id',
        'wo_name',
        'instructions',
        'tags',
        'priority',
        'work_status',
        'date',
        'time',
        'type',
        'sand_to',
        'location_id',
        'created_by',
        'company_id',
        'workspace',
        'status',
    ];

    
    protected static function newFactory()
    {
        return \Modules\CMMS\Database\factories\WorkorderFactory::new();
    }

    public static function priority()
    {

        $priority = [
            [
                'priority' => 'High Priority',
                'color' => 'danger',
            ],
            [
                'priority' => 'Medium Priority',
                'color' => 'warning',
            ],
            [
                'priority' => 'Low Priority',
                'color' => 'primary',
            ],
        ];
        return $priority;
        
    }

    public function getLocation()
    {
        return $this->hasOne('Modules\CMMS\Entities\Location', 'id', 'location_id');
    }

    public static function wosstatus()
    {

        $wosstatus = [
            [
                'work_status' => 'Open'
            ],
            [
                'work_status' => 'In Progress'
            ],
            [
                'work_status' => 'Planning'
            ],
            [
                'work_status' => 'Scheduling'
            ],
            [
                'work_status' => 'Suspended'
            ],
        ];
        return $wosstatus;
    }

    public static function assignTo($id){
        $user=User::where('id',$id)->select('id','name')->first();
        if(!(empty($user)))
        {
            return $user->name;
        }

    }
}
