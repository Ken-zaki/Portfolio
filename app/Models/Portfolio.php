<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Portfolio extends Model
{
    protected $fillable = [
        'user_id',
        'full_name',
        'title',
        'email',
        'phone',
        'location',
        'bio',
        'profile_image',
        'greeting',
        'hero_description',
        'about_content',
        'my_journey',
        'projects_completed',
        'years_experience',
        'happy_clients',
        'satisfaction_rate',
        'frontend_skills',
        'backend_skills',
        'tools_skills',
        'technologies',
        'education',
        'projects',
        'github_url',
        'linkedin_url',
        'twitter_url',
        'is_public',
    ];

    protected $casts = [
        'frontend_skills' => 'array',
        'backend_skills' => 'array',
        'tools_skills' => 'array',
        'technologies' => 'array',
        'education' => 'array',
        'projects' => 'array',
        'is_public' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
