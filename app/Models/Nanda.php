<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Nanda extends Model
{
    protected $fillable = [
        'code',
        'label',
        'label_es',
        'description', // This will store the Definition
        'description_es',
        'class_id',
        'approval_year',
        'evidence_level',
        'mesh_term',
        'focus',
        'symptoms_context',
        'care_subject',
        'judgment',
        'anatomical_location',
        'age_limit_lower',
        'age_limit_upper',
        'clinical_course',
        'diagnosis_status',
        'situational_limitation',
        'risk_factors',
        'at_risk_population',
        'associated_conditions',
    ];

    protected $casts = [
        'risk_factors' => 'array',
        'at_risk_population' => 'array',
        'associated_conditions' => 'array',
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
