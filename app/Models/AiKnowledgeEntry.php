<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AiKnowledgeEntry extends Model
{
    use HasFactory;

    protected $table = 'ai_knowledge_entries';

    protected $fillable = [
        'title',
        'content',
        'search_keywords',
        'category',
        'is_active',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'priority' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPriority($query)
    {
        return $query->orderByDesc('priority')->orderBy('title');
    }

    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }
}
