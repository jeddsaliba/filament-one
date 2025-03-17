<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\MediaCollectionType;
use App\Models\Traits\HasActivityLogs;
use App\Models\Traits\HasMediaConvertionRegistrations;
use App\Models\Traits\HasMessages;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasAvatar;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Jeffgreco13\FilamentBreezy\Traits\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;
use Parallax\FilamentComments\Models\Traits\HasFilamentComments;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser, HasAvatar, HasMedia
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasActivityLogs, HasApiTokens, HasFactory, HasFilamentComments, HasMessages, HasMediaConvertionRegistrations, HasPanelShield, HasRoles, Notifiable, SoftDeletes, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'deleted_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user profile associated with the User
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<UserProfile>
     */
    public function userProfile(): HasOne
    {
        return $this->hasOne(UserProfile::class);
    }

    /**
     * Register media collections for the User model.
     * 
     * This method adds a media collection for 'USER_PROFILE' and registers
     * media conversions using the defined conversion registrations.
     * 
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionType::USER_PROFILE->value)
            ->registerMediaConversions($this->modelMediaConvertionRegistrations());
    }

    /**
     * Get the user's avatar.
     *
     * This method returns a MorphOne relationship to the media table
     * where the collection_name is equal to the 'USER_PROFILE' enum value.
     *
     * @return MorphOne<Media>
     */
    public function avatar(): MorphOne
    {
        return $this->morphOne(Media::class, 'model')
            ->where('collection_name', MediaCollectionType::USER_PROFILE);
    }

    /**
     * Returns the URL of the user's avatar. This is used by the Filament package
     * to display the user's avatar in the navigation menu.
     *
     * @return string|null
     */
    public function getFilamentAvatarUrl(): ?string
    {
        return $this->avatar?->getFullUrl();
    }
}
