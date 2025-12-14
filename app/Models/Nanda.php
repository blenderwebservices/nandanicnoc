<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nanda extends Model
{
    protected $fillable = [
        'code',
        'label',
        'description',
        'class_id',
    ];

    public function nandaClass()
    {
        return $this->belongsTo(NandaClass::class, 'class_id');
    }

    protected static function booted()
    {
        static::saved(function ($model) {
            // Update FTS index
            \DB::table('nanda_search_index')->where('diagnosis_id', $model->id)->delete();

            if ($model->nandaClass) {
                \DB::table('nanda_search_index')->insert([
                    'diagnosis_id' => $model->id,
                    'class_id' => $model->class_id,
                    'domain_name' => $model->nandaClass->domain->name ?? '',
                    'class_name' => $model->nandaClass->name,
                    'class_definition' => $model->nandaClass->definition,
                    'diagnosis_code' => $model->code,
                    'diagnosis_label' => $model->label,
                    'diagnosis_definition' => $model->description,
                ]);
            }
        });

        static::deleted(function ($model) {
            \DB::table('nanda_search_index')->where('diagnosis_id', $model->id)->delete();
        });
    }
}
