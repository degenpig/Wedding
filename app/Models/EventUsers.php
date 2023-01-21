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

class EventUsers extends LocalizableModel
{
    use HasFactory, SoftDeletes;

    public $table = 'event_users';

    protected $fillable = ['event_id', 'user_id'];

    protected $appends = ['created', 'updated'];

    protected $casts = [
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

    public function event()
    {
        return $this->belongsTo(Events::class,'event_id');
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

    public function getActiveStatusAttribute($value)
    {
        $active = 'Inactive';

        if($this->active == 1){
            $active = 'Active';
        }
        
        return $active;
    } 
}
