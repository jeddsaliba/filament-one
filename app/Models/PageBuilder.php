<?php

namespace App\Models;

use App\Models\Traits\HasActivityLogs;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Parallax\FilamentComments\Models\Traits\HasFilamentComments;

class PageBuilder extends Model
{
    use HasActivityLogs, HasFactory, HasFilamentComments, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'description',
        'is_active',
        'content',
        'custom_css',
        'custom_js',
        'meta',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'meta' => 'array',
        ];
    }

    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn () => config('app.url') . "/{$this->slug}"
        );
    }
}
