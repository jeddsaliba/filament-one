<?php

namespace App\Models;

use App\Enums\MediaCollectionType;
use App\Models\Traits\HasActivityLogs;
use App\Models\Traits\HasMediaConvertionRegistrations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Message extends Model implements HasMedia
{
    use HasActivityLogs, HasFactory, HasMediaConvertionRegistrations, SoftDeletes;

    protected $table = 'fm_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'inbox_id',
        'message',
        'user_id',
        'read_by',
        'read_at',
        'notified',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'read_by' => 'array',
            'read_at' => 'array',
            'notified' => 'array',
        ];
    }

    /**
     * Register media collections for the Message model.
     * 
     * This method adds a media collection for 'FILAMENT_MESSAGES' and registers
     * media conversions using the defined conversion registrations.
     * 
     * @return void
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection(MediaCollectionType::FILAMENT_MESSAGES->value)
            ->registerMediaConversions($this->modelMediaConvertionRegistrations());
    }

    /**
     * Returns a morph many relationship to the media table where the
     * collection_name is equal to the 'FILAMENT_MESSAGES' enum value.
     * 
     * This relationship is used to fetch the attachments for the message.
     * 
     * @return MorphMany<Media>
     */
    public function attachments(): MorphMany
    {
        return $this->morphMany(Media::class, 'model')
            ->where('collection_name', MediaCollectionType::FILAMENT_MESSAGES);
    }

    /**
     * Get the user that sent the message.
     *
     * This relationship links the message to the user who sent it.
     *
     * @return BelongsTo<User>
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the inbox that this message belongs to.
     *
     * This relationship links the message to its parent inbox.
     *
     * @return BelongsTo<Inbox>
     */
    public function inbox(): BelongsTo
    {
        return $this->belongsTo(Inbox::class);
    }
}