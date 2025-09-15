<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PropertyInquiry extends Model
{
    protected $fillable = [
        'property_id',
        'name',
        'email',
        'phone',
        'inquiry_type',
        'message',
        'is_qualified_buyer',
        'max_budget',
        'needs_financing',
        'additional_info',
        'status',
        'contacted_at',
        'assigned_to',
        'agent_notes',
        'ip_address',
        'user_agent',
        'referral_source'
    ];

    protected $casts = [
        'is_qualified_buyer' => 'boolean',
        'needs_financing' => 'boolean',
        'max_budget' => 'decimal:2',
        'contacted_at' => 'datetime'
    ];

    // Relationships
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function assignedAgent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    // Accessors
    public function getInquiryTypeDisplayAttribute()
    {
        return match($this->inquiry_type) {
            'general' => 'General Information',
            'showing' => 'Schedule Showing',
            'offer' => 'Make Offer',
            'financing' => 'Financing Questions',
            'information' => 'Property Information'
        };
    }

    public function getStatusDisplayAttribute()
    {
        return match($this->status) {
            'new' => 'New',
            'contacted' => 'Contacted',
            'qualified' => 'Qualified',
            'converted' => 'Converted',
            'closed' => 'Closed'
        };
    }

    public function getFormattedBudgetAttribute()
    {
        return $this->max_budget ? '$' . number_format($this->max_budget) : null;
    }

    // Scopes
    public function scopeNew($query)
    {
        return $query->where('status', 'new');
    }

    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('inquiry_type', $type);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
