<?php

namespace App\Models;

use App\Models\NandaClass;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = [
        'code',
        'name',
        'name_es',
    ];

    public function classes()
    {
        return $this->hasMany(NandaClass::class);
    }

    public function getNameAttribute($value)
    {
        if (app()->getLocale() === 'es' && !empty($this->attributes['name_es'])) {
            return $this->attributes['name_es'];
        }
        return $value;
    }
}
