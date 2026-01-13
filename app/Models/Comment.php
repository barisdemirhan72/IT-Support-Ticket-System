<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'comment',
        'is_internal',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_internal' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the ticket that owns the comment.
     */
    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the user who created the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include public comments.
     */
    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    /**
     * Scope a query to only include internal comments.
     */
    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    /**
     * Check if the comment is internal.
     */
    public function isInternal(): bool
    {
        return $this->is_internal;
    }

    /**
     * Check if the comment is public.
     */
    public function isPublic(): bool
    {
        return !$this->is_internal;
    }

    /**
     * Check if a user can view this comment.
     */
    public function canBeViewedBy(User $user): bool
    {
        // Internal comments can only be viewed by support staff
        if ($this->is_internal) {
            return $user->isSupport();
        }

        // Public comments can be viewed by the ticket owner or support staff
        return $user->id === $this->ticket->user_id || $user->isSupport();
    }

    /**
     * Get the author name attribute.
     */
    public function getAuthorNameAttribute(): string
    {
        return $this->user->name;
    }

    /**
     * Get formatted created at time.
     */
    public function getFormattedCreatedAtAttribute(): string
    {
        return $this->created_at->format('M d, Y H:i');
    }
}
