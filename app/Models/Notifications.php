<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule; 
use App\LocalizableModel;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;

use Validator;

class Notifications extends LocalizableModel
{
    use HasFactory, SoftDeletes;

    public $table = 'notifications';

    protected $fillable = ['user_id', 'title', 'message', 'read'];

    protected $appends = ['read_status', 'created', 'updated', 'read_time'];

    protected $casts = [
        'read_at'    => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $localizable = [
        
    ];


    protected function validator(array $data, $id)
    {
        $validate = Validator::make($data, [
            

        ]);
        return $validate;
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
    
    public function getCreatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    }

    public function getUpdatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    }

    public function getReadTimeAttribute($value)
    {
        return Carbon::parse($this->read_at)->format('Y-m-d H:i A');
    }

    public function getReadStatusAttribute($value)
    {
        $active = 'Inactive';

        if($this->active == 1){
            $active = 'Active';
        }
        
        return $active;
    } 
}
