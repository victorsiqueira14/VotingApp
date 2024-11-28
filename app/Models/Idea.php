<?php

namespace App\Models;

use App\Exceptions\DuplicateVoteException;
use App\Exceptions\VoteNotFoundException;
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

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * A status belongs to a Idea
     *
     * @return void
     */
    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    /**
     * A votes belongs to many users
     *
     * @return void
     */
    public function votes()
    {
        return $this->belongsToMany(User::class, 'votes');
    }

    /**
     * if is voted by user
     *
     * @param User|null $user
     * @return boolean
     */
    public function isVotedByUser(?User $user)
    {
        if (!$user) {
            return false;
        }

        return Vote::where('user_id', $user->id)
            ->where('idea_id', $this->id)
            ->exists();
    }

    /**
     * create a vote for a user
     *
     * @param User $user
     * @return void
     */
    public function vote(User $user)
    {

        if($this->isVotedByUser($user)) {
            throw new DuplicateVoteException;
        }

        Vote::create([
            'idea_id' => $this->id,
            'user_id' => $user->id,
        ]);
    }

    /**
     * remove a vote for the user
     *
     * @param User $user
     * @return void
     */
    public function removeVote(User $user)
    {
        $voteToDelete = Vote::where('idea_id', $this->id)
        ->where('user_id', $user->id)
        ->first();

        if($voteToDelete) {
            $voteToDelete->delete();
        } else {
            throw new VoteNotFoundException;
        }
    }
}
