<?php

namespace App\Models;

use App\Models\NandaClass;
use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $fillable = [
        'code',
        'name',
    ];

    public function classes()
    {
        return $this->hasMany(NandaClass::class);
    }
}
