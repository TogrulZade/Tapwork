<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class JobPost extends Model
{
    // use Searchable;

    protected $fillable = [
        'title',
        'url',
        'description',
        'company_id',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'string',
        'end_date' => 'string',
    ];

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'company_name' => $this->company ? $this->company->name : null,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ];
    }

    public function searchableAs()
    {
        return env('MEILISEARCH_PREFIX', '') . 'job_posts';
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
