<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Nanda;

class Nic extends Model
{
    protected $fillable = [
        'code',
        'label',
        'label_es',
        'definition',
        'definition_es',
        'activities',
        'activities_es',
    ];

    protected $casts = [
        'activities' => 'array',
        'activities_es' => 'array',
    ];

    public function nandas()
    {
        return $this->belongsToMany(Nanda::class, 'nanda_nic', 'nic_id', 'nanda_id')
            ->withPivot('type')
            ->withTimestamps();
    }
}
