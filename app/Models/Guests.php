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

class Guests extends LocalizableModel
{
    use HasFactory, SoftDeletes;

    public $table = 'guests';

    protected $fillable = ['user_id', 'event_id', 'group_id', 'heading_title_id', 'first_name', 'second_name', 'third_name', 'family_name', 'email_id', 'phone_code', 'phone_number', 'whatsapp_phone_code', 'whatsapp_phone_number', 'dob', 'age', 'companian_available'];

    protected $appends = ['companian_status', 'invitation_status_id', 'invitation_status_title', 'created', 'updated'];

    //'heading_title_name', 

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

    public function heading_title()
    {
        return $this->belongsTo(HeadingTitles::class,'heading_title_id');
    }

    public function companian()
    {
        return $this->hasMany(Companians::class,'guest_id');
    }

    public function invitation()
    {
        return $this->hasOne(Invitations::class,'receiver_id');
    }

    
    public function getCreatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    }

    public function getUpdatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    }

    public function getInvitationStatusIdAttribute($value)
    {  
        $invitation = Invitations::where('receiver_id', $this->id)->with(['invitation_status'])->first();
        
        $id = (isset($invitation) && isset($invitation->invitation_status)) ? $invitation->invitation_status->id : 1;

        return $id;
    } 
    public function getInvitationStatusTitleAttribute($value)
    {  
        $invitation = Invitations::where('receiver_id', $this->id)->with(['invitation_status'])->first();
        
        $status = (isset($invitation) && isset($invitation->invitation_status)) ? $invitation->invitation_status->title : 'Not Yet Invited';

        return $status;
    } 

    public function getCompanianStatusAttribute($value)
    {
        $companian = 'Not Available';

        if($this->companian_available == 1){
            $companian = 'Available';
        }
        
        return $companian;
    } 

    public function getCompanianDataAttribute($value)
    { 
        return $this->hasMany(Companians::class,'guest_id');
    } 

    public function scopeInvitationStatusId($query, $searchStatus) {

        $searchStatus = explode(',', $searchStatus);  
        
        return $query->whereExists(function ($query) use ($searchStatus) {
            $query->select(DB::raw(1))
                  ->from('invitations')
                  ->whereRaw('guests.id = invitations.receiver_id')
                  ->whereIn('invitations.invitation_status_id', $searchStatus);
        });
    }
    
}
