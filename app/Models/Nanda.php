<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nanda extends Model
{
    protected $fillable = [
        'code',
        'label',
        'label_es',
        'description',
        'description_es',
        'class_id',
    ];

    public function getLabelAttribute($value)
    {
        if (app()->getLocale() === 'es' && !empty($this->attributes['label_es'])) {
            return $this->attributes['label_es'];
        }
        return $value;
    }

    public function getDescriptionAttribute($value)
    {
        if (app()->getLocale() === 'es' && !empty($this->attributes['description_es'])) {
            return $this->attributes['description_es'];
        }
        return $value;
    }

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
                    'domain_name_es' => $model->nandaClass->domain->name_es ?? '',
                    'class_name' => $model->nandaClass->name,
                    'class_name_es' => $model->nandaClass->name_es ?? '',
                    'class_definition' => $model->nandaClass->definition,
                    'class_definition_es' => $model->nandaClass->definition_es ?? '',
                    'diagnosis_code' => $model->code,
                    'diagnosis_label' => $model->label,
                    'diagnosis_label_es' => $model->label_es ?? '',
                    'diagnosis_definition' => $model->description,
                    'diagnosis_definition_es' => $model->description_es ?? '',
                ]);
            }
        });

        static::deleted(function ($model) {
            \DB::table('nanda_search_index')->where('diagnosis_id', $model->id)->delete();
        });
    }
}
