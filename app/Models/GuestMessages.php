<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Validation\Rule; 
use App\LocalizableModel;
use Carbon\Carbon; 
use Illuminate\Support\Facades\DB;
use App\Models\Statuses;

use Validator;

class GuestMessages extends LocalizableModel
{
    use HasFactory, SoftDeletes;

    public $table = 'guest_messages';

    protected $fillable = ['user_id', 'event_id', 'group_id', 'guest_id', 'message'];
    
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

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function event()
    {
        return $this->belongsTo(Events::class,'event_id');
    }

    public function group()
    {
        return $this->belongsTo(Groups::class,'group_id');
    }

    public function guest()
    {
        return $this->belongsTo(Guests::class,'guest_id');
    }

    
    public function getCreatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    }

    public function getUpdatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    }
}
