<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Assets\Entities\Asset;

class BorrowAssetRecord extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'user_id',
        'asset_id',
        'employee_id',
        'status',
        'borrowed_date',
        'borrowed_day',
        'give_back_day',
        'description'

    ];

    public static $statues = [
        'Chờ phê duyệt',
        'Phê duyệt',
        'Từ chối',
        'Đã trả',
        'Thu hồi'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
    
}
