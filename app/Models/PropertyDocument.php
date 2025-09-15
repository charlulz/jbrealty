<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class PropertyDocument extends Model
{
    protected $fillable = [
        'property_id',
        'title',
        'description',
        'filename',
        'path',
        'url',
        'type',
        'file_size',
        'mime_type',
        'is_public',
        'requires_nda',
        'sort_order',
        'uploaded_by'
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'requires_nda' => 'boolean',
        'file_size' => 'integer',
        'sort_order' => 'integer'
    ];

    // Relationships
    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Accessors
    public function getUrlAttribute($value)
    {
        if ($value) {
            return $value;
        }

        if ($this->path) {
            return Storage::url($this->path);
        }

        return null;
    }

    public function getFormattedFileSizeAttribute()
    {
        if (!$this->file_size) {
            return null;
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getTypeDisplayAttribute()
    {
        return match($this->type) {
            'deed' => 'Deed',
            'survey' => 'Survey',
            'plat' => 'Plat Map',
            'appraisal' => 'Appraisal',
            'environmental' => 'Environmental Report',
            'lease' => 'Lease Agreement',
            'easement' => 'Easement',
            'tax_record' => 'Tax Record',
            'zoning' => 'Zoning Information',
            'inspection' => 'Inspection Report',
            'other' => 'Other Document'
        };
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }
}
