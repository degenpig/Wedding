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

class WeddingUsers extends LocalizableModel
{
    use HasFactory, SoftDeletes;

    public $table = 'wedding_users';

    protected $fillable = ['owner_id', 'heading_title_id', 'type', 'first_name', 'second_name', 'third_name', 'family_name', 'father_name', 'mother_name', 'email_id', 'phone_code', 'phone_number', 'dob', 'age', 'civil_id_number', 'civil_id_image', 'nationality', 'active'];

    protected $appends = ['type_name', 'active_status', 'created', 'updated'];

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

    public function heading_title()
    {
        return $this->belongsTo(HeadingTitles::class,'heading_title_id');
    }

    
    public function getCreatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    }

    public function getUpdatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    }

    public function getCivilIdImageAttribute($value)
    {
        $url = \config('app.bucket_url');

        if($value != ''){
            return $url.$value;
        }else{
            return null;
        }
    }

    public function getTypeNameAttribute($value)
    {
        $type = 'Groom';

        if($this->type == 1){
            $type = 'Bride';
        }

        return $type;
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
