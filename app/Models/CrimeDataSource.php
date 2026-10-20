<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CrimeDataSource extends Model
{
    protected $fillable = [
        'name',
        'agency_name',
        'source_url',
        'api_endpoint',
        'country',
        'state',
        'city',
        'county',
        'zip_code',
        'coverage_type',
        'data_format',
        'field_mapping',
        'query_config',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'field_mapping' => 'array',
            'query_config' => 'array',
            'is_active' => 'boolean',
        ];
    }
}
