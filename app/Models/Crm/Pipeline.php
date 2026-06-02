<?php

namespace App\Models\Crm;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pipeline extends Model
{
    protected $table = 'crm_pipelines';

    protected $fillable = ['name', 'position', 'is_default'];

    protected $casts = ['is_default' => 'boolean'];

    public function stages(): HasMany
    {
        return $this->hasMany(Stage::class, 'pipeline_id')->orderBy('position');
    }

    public function deals(): HasMany
    {
        return $this->hasMany(Deal::class, 'pipeline_id');
    }

    public static function default(): self
    {
        return static::where('is_default', true)->first() ?? static::orderBy('position')->firstOrFail();
    }
}
