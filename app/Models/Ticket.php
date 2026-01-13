<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'status',
        'priority',
        'user_id',
        'category_id',
        'assigned_to',
        'resolved_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Available status values.
     */
    const STATUS_NEW = 'new';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CLOSED = 'closed';
    const STATUS_REJECTED = 'rejected';

    /**
     * Available priority values.
     */
    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Get the user who created the ticket.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the category of the ticket.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the support staff assigned to the ticket.
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get the comments for the ticket.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'asc');
    }

    /**
     * Scope a query to only include tickets of a specific status.
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include open tickets.
     */
    public function scopeOpen($query)
    {
        return $query->whereIn('status', [self::STATUS_NEW, self::STATUS_IN_PROGRESS]);
    }

    /**
     * Scope a query to only include closed tickets.
     */
    public function scopeClosed($query)
    {
        return $query->whereIn('status', [self::STATUS_COMPLETED, self::STATUS_CLOSED, self::STATUS_REJECTED]);
    }

    /**
     * Scope a query to only include tickets by priority.
     */
    public function scopePriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope a query to only include tickets for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope a query to only include tickets assigned to a specific user.
     */
    public function scopeAssignedToUser($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope a query to only include tickets in a specific category.
     */
    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Check if the ticket is open.
     */
    public function isOpen(): bool
    {
        return in_array($this->status, [self::STATUS_NEW, self::STATUS_IN_PROGRESS]);
    }

    /**
     * Check if the ticket is closed.
     */
    public function isClosed(): bool
    {
        return in_array($this->status, [self::STATUS_COMPLETED, self::STATUS_CLOSED, self::STATUS_REJECTED]);
    }

    /**
     * Check if the ticket is new.
     */
    public function isNew(): bool
    {
        return $this->status === self::STATUS_NEW;
    }

    /**
     * Check if the ticket is in progress.
     */
    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    /**
     * Check if the ticket is completed.
     */
    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    /**
     * Update the ticket status.
     */
    public function updateStatus(string $newStatus): bool
    {
        $oldStatus = $this->status;
        $this->status = $newStatus;

        if ($newStatus === self::STATUS_COMPLETED && !$this->resolved_at) {
            $this->resolved_at = now();
        }

        $saved = $this->save();

        if ($saved && $oldStatus !== $newStatus) {
            // Trigger event for status change notification
            event(new \App\Events\TicketStatusChanged($this, $oldStatus, $newStatus));
        }

        return $saved;
    }

    /**
     * Assign the ticket to a support staff member.
     */
    public function assignTo(?int $userId): bool
    {
        $this->assigned_to = $userId;
        return $this->save();
    }

    /**
     * Add a comment to the ticket.
     */
    public function addComment(int $userId, string $comment, bool $isInternal = false): Comment
    {
        $commentModel = $this->comments()->create([
            'user_id' => $userId,
            'comment' => $comment,
            'is_internal' => $isInternal,
        ]);

        // Trigger event for comment notification
        event(new \App\Events\CommentAdded($commentModel));

        return $commentModel;
    }

    /**
     * Get formatted status label.
     */
    public function getStatusLabelAttribute(): string
    {
        return ucwords(str_replace('_', ' ', $this->status));
    }

    /**
     * Get formatted priority label.
     */
    public function getPriorityLabelAttribute(): string
    {
        return ucfirst($this->priority);
    }

    /**
     * Get status badge color for UI.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            self::STATUS_NEW => 'blue',
            self::STATUS_IN_PROGRESS => 'yellow',
            self::STATUS_COMPLETED => 'green',
            self::STATUS_CLOSED => 'gray',
            self::STATUS_REJECTED => 'red',
            default => 'gray',
        };
    }

    /**
     * Get priority badge color for UI.
     */
    public function getPriorityColorAttribute(): string
    {
        return match($this->priority) {
            self::PRIORITY_LOW => 'gray',
            self::PRIORITY_MEDIUM => 'blue',
            self::PRIORITY_HIGH => 'orange',
            self::PRIORITY_URGENT => 'red',
            default => 'gray',
        };
    }

    /**
     * Get all available statuses.
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_NEW,
            self::STATUS_IN_PROGRESS,
            self::STATUS_COMPLETED,
            self::STATUS_CLOSED,
            self::STATUS_REJECTED,
        ];
    }

    /**
     * Get all available priorities.
     */
    public static function getPriorities(): array
    {
        return [
            self::PRIORITY_LOW,
            self::PRIORITY_MEDIUM,
            self::PRIORITY_HIGH,
            self::PRIORITY_URGENT,
        ];
    }
}
