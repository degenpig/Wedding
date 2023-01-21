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

class Statuses extends LocalizableModel
{
    use HasFactory, SoftDeletes;

    public $table = 'statuses';

    protected $fillable = ['title'];
    
    protected $appends = ['created', 'updated'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected function validatorAdd(array $data, $id)
    {
        $validate = Validator::make($data, [
            'title' => [
                'required',
                Rule::unique('statuses')->where(function ($query) use($data,$id) {
                    return $query->where('id', '<>', $id)
                    ->where('title', $data['title'])
                    ->where('deleted_at', NULL);
                }),
            ],
        ]);
        return $validate;
    }

    protected function validatorUpdate(array $data, $id)
    {
        $validate = Validator::make($data, [
            'id' => 'required|int', 
            'title' => [
                'required',
                Rule::unique('statuses')->where(function ($query) use($data,$id) {
                    return $query->where('id', '<>', $id)
                    ->where('title', $data['title'])
                    ->where('deleted_at', NULL);
                }),
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

    protected function validator(array $data, $id)
    {
        $validate = Validator::make($data, [
            

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
}
