<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Events;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use mysql_xdevapi\Exception;
use Validator;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public $table = 'users';

    protected $fillable = [
        'role',
        'name',
        'email',
        'password',
    ];

    protected $appends = ['role_name','event_available','event', 'created', 'updated'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'created_at'        => 'datetime',
        'updated_at'        => 'datetime',
        'deleted_at'        => 'datetime',
    ];

    protected function validatorAdd(array $data, $id)
    {
        $validate = Validator::make($data, [
            'role' => 'required|int',
            'name' => [
                'required',
                Rule::unique('users')->where(function ($query) use($data,$id) {
                    return $query->where('id', '<>', $id)
                    ->where('name', $data['name'])
                    ->where('deleted_at', NULL);
                }),
            ],
            'email' => [
                'required',
                Rule::unique('users')->where(function ($query) use($data,$id) {
                    return $query->where('id', '<>', $id)
                    ->where('email', $data['email'])
                    ->where('deleted_at', NULL);
                }),
                'email:strict',
            ],
            'password' => 'required|string|min:6|max:155',


        ]);
        return $validate;
    }

    protected function validatorUpdate(array $data, $id)
    {
        $validate = Validator::make($data, [
            'id' => 'required|int',
            'role' => 'required|int',
            'name' => [
                'required',
                Rule::unique('users')->where(function ($query) use($data,$id) {
                    return $query->where('id', '<>', $id)
                    ->where('name', $data['name'])
                    ->where('deleted_at', NULL);
                }),
            ],
            'email' => [
                'required',
                Rule::unique('users')->where(function ($query) use($data,$id) {
                    return $query->where('id', '<>', $id)
                    ->where('email', $data['email'])
                    ->where('deleted_at', NULL);
                }),
                'email:strict',
            ],


        ]);
        return $validate;
    }

    protected function validatorDelete(array $data, $id)
    {
        $validate = Validator::make($data, [
            'id' => 'required|int',
        ]);
        return $validate;
    }

    public function getCreatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    }

    public function getUpdatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    }

    public function getRoleNameAttribute($value)
    {
        $rolename = 'Event Owner Access';

        if($this->role == 1){
            $rolename = 'Event Users Access';
        }elseif($this->role == 2){
            $rolename = 'Delegate Access';
        }

        return $rolename;
    }

    public function getEventAvailableAttribute($value)
    {
        try {
            $event = Events::where('owner_id', $this->id)->first();
            if($event)
            {
                return 'available';
            }
            else {
                return 'not_available';
            }
        }
        catch (Exception $exception) {
            return 'not_available';
        }


    }

    public function getEventAttribute($value)
    {
        try {
            $event = Events::where('owner_id', $this->id)->first();
            if($event)
            {
                return $event;
            }
            else {
                return null;
            }
        }
        catch (Exception $exception) {
            return null;
        }
    }
}
