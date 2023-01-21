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

class InvitationSends extends LocalizableModel
{
    use HasFactory, SoftDeletes;

    public $table = 'invitation_sends';

    protected $fillable = ['type', 'title', 'welcome_message', 'sender_id', 'receiver_id', 'companian_id', 'invitation_type_id', 'invitation_id'];

    protected $appends = ['type_name', 'created', 'updated'];

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

    public function group()
    {
        return $this->belongsTo(Groups::class,'group_id');
    }
    
    public function sender()
    {
        return $this->belongsTo(User::class,'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(Guests::class,'receiver_id');
    }

    public function companian()
    {
        return $this->belongsTo(Companians::class,'companian_id');
    }

    public function invitation_type()
    {
        return $this->belongsTo(InvitationTypes::class,'invitation_type_id');
    }

    public function invitation()
    {
        return $this->belongsTo(Invitations::class,'invitation_id');
    } 
     
    public function getCreatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    }

    public function getUpdatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    } 

    public function getTypeNameAttribute($value)
    {
        $type = 'SMS';

        if($this->type == 2){
            $type = 'Email';
        }elseif($this->type == 3){
            $type = 'WhatsApp';
        }

        return $type;
    } 
}
