<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Nanda; // Assuming Nanda model is in the same namespace or needs to be imported

class Noc extends Model
{
    protected $fillable = [
        'code',
        'label',
        'label_es',
        'definition',
        'definition_es',
        'indicators',
        'indicators_es',
    ];

    protected $casts = [
        'indicators' => 'array',
        'indicators_es' => 'array',
    ];

    public function nandas()
    {
        return $this->belongsToMany(Nanda::class, 'nanda_noc', 'noc_id', 'nanda_id')
            ->withPivot('type')
            ->withTimestamps();
    }
}
