<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use App\Models\Status;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;

class IdeaTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_check_if_idea_is_voted_for_by_user()
    {
        $user = User::factory()->create();
        $userB = User::factory()->create();

        $categoryOne = Category::factory()->create(['name' => 'category 1']);

        $statusOpen = Status::factory()->create(['name' => 'Open', 'classes' => 'bg-gray-200']);

        $idea = Idea::factory()->create([
            'user_id' => $user->id,
            'category_id' => $categoryOne->id,
            'status_id' => $statusOpen->id,
            'title' => 'My First Idea',
            'description' => 'Description for my first idea',
        ]);

        Vote::factory()->create([
            'idea_id' => $idea->id,
            'user_id' => $user->id
        ]);

        $this->assertTrue($idea->isVotedByUser($user));
        $this->assertFalse($idea->isVotedByUser($userB));
        $this->assertFalse($idea->isVotedByUser(null));
    }

}
