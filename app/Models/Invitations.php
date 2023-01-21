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

class Invitations extends LocalizableModel
{
    use HasFactory, SoftDeletes;

    public $table = 'invitations';

    protected $fillable = ['invitation_code', 'event_id', 'sender_id', 'receiver_id', 'invitation_type_id', 'invitation_status_id', 'receiver_companian', 'active'];

    protected $appends = ['active_status', 'created', 'updated'];
    //'receiver_companian_status', 'receiver_companian_data', 

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
    
    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Guests::class,'receiver_id');
    }

    public function invitation_type()
    {
        return $this->belongsTo(InvitationTypes::class,'invitation_type_id');
    }

    public function invitation_status()
    {
        return $this->belongsTo(Statuses::class,'invitation_status_id');
    } 

    public function receiver_companian()
    { 
        return $this->hasMany(Companians::class,'receiver_id');
    }  
     
    public function getCreatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    }

    public function getUpdatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    } 

    public function getReceiverCompanianStatusAttribute($value)
    {
        $companian = 'Not Available';

        if($this->companian_available == 1){
            $companian = 'Available';
        }
        
        return $companian;
    } 

    public function getReceiverCompanianDataAttribute($value)
    { 
        return $this->hasMany(Companians::class,'receiver_id');
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
