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
        'definition',
    ];

    public function domain()
    {
        return $this->belongsTo(Domain::class);
    }

    public function diagnoses()
    {
        return $this->hasMany(Nanda::class, 'class_id');
    }
}
