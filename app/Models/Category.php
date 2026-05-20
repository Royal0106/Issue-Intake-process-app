<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    //
    protected $fillable = ['name'];

    // Relationship: A category has many issues
    public function issues()
    {
        return $this->hasMany(Issue::class);
    }
}
