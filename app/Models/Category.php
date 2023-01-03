<?php

namespace App\Models;

use App\Models\Idea;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    /**
     * A category has many Ideas
     *
     * @return void
     */
    public function ideas()
    {
        return $this->hasMany(Idea::class);
    }
}
