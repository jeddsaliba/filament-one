<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Inbox extends Model
{
    use SoftDeletes;

    protected $table = 'fm_inboxes';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'user_ids'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_ids' => 'array',
        ];
    }

    protected function inboxTitle(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->title ?? collect($this->user_ids)->filter(fn ($userId) => $userId != Auth::id())->map(function ($userId) {
                return \App\Models\User::find($userId)?->name;
            })->values()->implode(', ')
        );
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function latestMessage()
    {
        return $this->messages()->latest()->first();
    }

    public function otherUsers(): Attribute
    {
        return Attribute::make(
            get: fn () => \App\Models\User::whereIn('id', $this->user_ids)->whereNot('id', Auth::id())->get()
        );
    }
}