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

class Events extends LocalizableModel
{
    use HasFactory, SoftDeletes;

    public $table = 'events';

    protected $fillable = ['owner_id', 'groom_id', 'bride_id', 'event_title', 'welcome_msg', 'event_date', 'event_time', 'location', 'latitude', 'longitude', 'google_maps_url', 'active', 'deleted', 'priority'];

    protected $appends = ['active_status', 'created', 'updated'];

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

    public function owner()
    {
        return $this->belongsTo(User::class,'owner_id');
    }

    public function groom()
    {
        return $this->belongsTo(WeddingUsers::class,'groom_id');
    }

    public function bride()
    {
        return $this->belongsTo(WeddingUsers::class,'bride_id');
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
