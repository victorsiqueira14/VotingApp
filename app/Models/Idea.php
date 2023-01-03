<?php

namespace App\Models;

use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Idea extends Model
{
    use HasFactory, Sluggable;

    const PAGINATION_COUNT = 10;

    protected $guarded = [];

    /**
     * Return the sluggable configuration array for this model
     *
     * @return array
     */
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    /**
     * A idea belongs to User
     * @return void
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * A category belongs to User
     *
     * @return void
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

}
