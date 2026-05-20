<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    use HasFactory;

    // If table name differs from convention
    // protected $table = 'issues';

    // Fields that can be mass-assigned
    protected $fillable = [
        'title',
        'description',
        'priority',
        'category_id',
        'status',
        'summary',
        'next_action'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}