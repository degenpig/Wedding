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

class HeadingTitles extends LocalizableModel
{
    use HasFactory, SoftDeletes;

    public $table = 'heading_titles';

    protected $fillable = ['title'];
    
    protected $appends = ['created', 'updated'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $localizable = [
        
    ];


    protected function validatorAdd(array $data, $id)
    {
        $validate = Validator::make($data, [
            'title' => [
                'required',
                Rule::unique('heading_titles')->where(function ($query) use($data,$id) {
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
                Rule::unique('heading_titles')->where(function ($query) use($data,$id) {
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
    
    public function getCreatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    }

    public function getUpdatedAttribute($value)
    {
        return Carbon::parse($this->created_at)->format('Y-m-d H:i A');
    } 
}
