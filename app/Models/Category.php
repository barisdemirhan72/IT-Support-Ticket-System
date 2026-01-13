<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the tickets for the category.
     */
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    /**
     * Scope a query to only include active categories.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Check if the category is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }

    /**
     * Get the number of tickets in this category.
     */
    public function getTicketCountAttribute(): int
    {
        return $this->tickets()->count();
    }

    /**
     * Get the number of open tickets in this category.
     */
    public function getOpenTicketCountAttribute(): int
    {
        return $this->tickets()
            ->whereIn('status', ['new', 'in_progress'])
            ->count();
    }
}
