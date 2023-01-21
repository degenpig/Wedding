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

class QrCodes extends LocalizableModel
{
    use HasFactory, SoftDeletes;

    public $table = 'qr_codes';

    protected $fillable = ['qr_code', 'event_id', 'group_id', 'user_id', 'guest_id', 'companian_id', 'invitation_type_id', 'invitation_id', 'scanned'];

    protected $appends = ['scanned_status', 'created', 'updated'];

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
    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function guest()
    {
        return $this->belongsTo(Guests::class,'guest_id');
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

    public function getScannedStatusAttribute($value)
    {
        $scanned = 'No';

        if($this->scanned == 1){
            $scanned = 'Yes';
        }
        
        return $scanned;
    } 
}
