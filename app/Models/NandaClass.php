<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Domain;
use App\Models\Nanda;

class NandaClass extends Model
{
    protected $fillable = [
        'domain_id',
        'code',
        'name',
        'name_es',
        'definition',
        'definition_es',
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function getNameAttribute($value)
    {
        if (app()->getLocale() === 'es' && !empty($this->attributes['name_es'])) {
            return $this->attributes['name_es'];
        }
        return $value;
    }

    public function getDefinitionAttribute($value)
    {
        if (app()->getLocale() === 'es' && !empty($this->attributes['definition_es'])) {
            return $this->attributes['definition_es'];
        }
        return $value;
    }

    public function diagnoses()
    {
        return $this->hasMany(Nanda::class, 'class_id');
    }
}
