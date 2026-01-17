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
        'approval_year',
        'year_revised',
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
        'defining_characteristics',
        'related_factors',
        'focus_es',
        'judgment_es',
        'diagnosis_status_es',
        'risk_factors_es',
        'at_risk_population_es',
        'associated_conditions_es',
        'defining_characteristics_es',
        'related_factors_es',
    ];

    protected $casts = [
        'risk_factors' => 'array',
        'at_risk_population' => 'array',
        'associated_conditions' => 'array',
        'defining_characteristics' => 'array',
        'related_factors' => 'array',
        'risk_factors_es' => 'array',
        'at_risk_population_es' => 'array',
        'associated_conditions_es' => 'array',
        'defining_characteristics_es' => 'array',
        'related_factors_es' => 'array',
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

    public function getFocusAttribute($value)
    {
        if (app()->getLocale() === 'es' && !empty($this->attributes['focus_es'])) {
            return $this->attributes['focus_es'];
        }
        return $value;
    }

    public function getJudgmentAttribute($value)
    {
        if (app()->getLocale() === 'es' && !empty($this->attributes['judgment_es'])) {
            return $this->attributes['judgment_es'];
        }
        return $value;
    }

    public function getDiagnosisStatusAttribute($value)
    {
        if (app()->getLocale() === 'es' && !empty($this->attributes['diagnosis_status_es'])) {
            return $this->attributes['diagnosis_status_es'];
        }
        return $value;
    }

    // For array attributes, we can't use simple getAttribute if the column exists separate
    // Actually we can, because Laravel uses the accessor if defined.
    // However, risk_factors is a direct column. 
    // We want to override the access.

    public function getRiskFactorsAttribute($value)
    {
        if (app()->getLocale() === 'es' && !empty($this->risk_factors_es)) {
            return $this->risk_factors_es;
        }
        return isset($value) ? json_decode($value, true) : [];
    }

    public function getAtRiskPopulationAttribute($value)
    {
        if (app()->getLocale() === 'es' && !empty($this->at_risk_population_es)) {
            return $this->at_risk_population_es;
        }
        return isset($value) ? json_decode($value, true) : [];
    }

    public function getAssociatedConditionsAttribute($value)
    {
        if (app()->getLocale() === 'es' && !empty($this->associated_conditions_es)) {
            return $this->associated_conditions_es;
        }
        return isset($value) ? json_decode($value, true) : [];
    }

    public function nandaClass()
    {
        return $this->belongsTo(NandaClass::class, 'class_id');
    }

    public function nics()
    {
        return $this->belongsToMany(Nic::class, 'nanda_nic', 'nanda_id', 'nic_id')
            ->withPivot('type')
            ->withTimestamps();
    }

    public function nocs()
    {
        return $this->belongsToMany(Noc::class, 'nanda_noc', 'nanda_id', 'noc_id')
            ->withPivot('type')
            ->withTimestamps();
    }

    public function getDefiningCharacteristicsAttribute($value)
    {
        if (app()->getLocale() === 'es' && !empty($this->defining_characteristics_es)) {
            return $this->defining_characteristics_es;
        }
        return isset($value) ? json_decode($value, true) : [];
    }

    public function getRelatedFactorsAttribute($value)
    {
        if (app()->getLocale() === 'es' && !empty($this->related_factors_es)) {
            return $this->related_factors_es;
        }
        return isset($value) ? json_decode($value, true) : [];
    }

    private static function compileProperties($model, $isEs)
    {
        $props = [];

        // Helper to flatten array or string
        $add = function ($val) use (&$props) {
            if (is_array($val)) {
                foreach ($val as $v) {
                    if (is_string($v))
                        $props[] = $v;
                }
            } elseif (is_string($val)) {
                $props[] = $val;
            }
        };

        $fields = [
            'defining_characteristics',
            'related_factors',
            'risk_factors',
            'at_risk_population',
            'associated_conditions'
        ];

        foreach ($fields as $field) {
            if ($isEs) {
                // Spanish columns have no accessors, so standard access works (casts to array)
                // Use ?? [] just in case it's null
                $add($model->{$field . '_es'} ?? []);
            } else {
                // English columns have accessors that might return Spanish based on locale.
                // We must bypass the accessor to get the original English data.
                // getAttributes() returns the raw array of attributes (usually JSON string for these fields).
                $raw = $model->getAttributes()[$field] ?? null;
                if (is_string($raw)) {
                    $val = json_decode($raw, true);
                    $add($val);
                } elseif (is_array($raw)) {
                    // In case it was already casted or set as array
                    $add($raw);
                }
            }
        }

        return implode(' ', $props);
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
                    'properties' => self::compileProperties($model, false),
                    'properties_es' => self::compileProperties($model, true),
                ]);
            }
        });

        static::deleted(function ($model) {
            \DB::table('nanda_search_index')->where('diagnosis_id', $model->id)->delete();
        });
    }
}
