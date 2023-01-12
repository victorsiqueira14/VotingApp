<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Status extends Model
{
    use HasFactory;

    /**
     * A idea has many Idea's
     *
     * @return void
     */
    public function idea()
    {
        return $this->hasMany(Idea::class);
    }
}
