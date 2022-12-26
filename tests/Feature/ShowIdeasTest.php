<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowIdeasTest extends TestCase
{
    use RefreshDatabase;

    public function test_lest_of_ideas_shows_on_main_page()
    {
        $ideaOne  = Idea::factory()->create([
            'title' => 'my first Idea',
            'description' => 'Description of my first Idea',
        ]);

        $ideaTwo  = Idea::factory()->create([
            'title' => 'my second Idea',
            'description' => 'Description of my second Idea',
        ]);

        $response = $this->get(route('idea.index'));

        $response->assertSuccessful();
        $response->assertSee($ideaOne->title);
        $response->assertSee($ideaOne->Description);
        $response->assertSee($ideaTwo->title);
        $response->assertSee($ideaTwo->Description);

    }

    public function test_a_sigle_idea_show_correcly_on_the_show_page()
    {
        $idea  = Idea::factory()->create([
            'title' => 'my first Idea',
            'description' => 'Description of my first Idea',
        ]);

        $response = $this->get(route('idea.index', $idea));

        $response->assertSuccessful();
        $response->assertSee($idea->title);
        $response->assertSee($idea->Description);

    }

    public function test_if_ideas_pagination_works()
    {
        Idea::factory(Idea::PAGINATION_COUNT + 1)->create();

        $ideaOne = Idea::find(1);
        $ideaOne->title = 'my first Idea';
        $ideaOne->save();

        $ideaEleven = Idea::find(11);
        $ideaEleven->title = 'my eleveth Idea';
        $ideaEleven->save();

        $response = $this->get('/');

        $response->assertSee($ideaOne->title);
        $response->assertDontSee($ideaEleven->title);

        $response = $this->get('/?page=2');

        $response->assertSee($ideaEleven->title);
        $response->assertDontSee($ideaOne->title);

    }

    public function test_if_same_idea_title_different_slugs()
    {
        $ideaOne  = Idea::factory()->create([
            'title' => 'my first idea',
            'description' => 'Description of my first Idea',
        ]);

        $ideaTwo  = Idea::factory()->create([
            'title' => 'my first idea',
            'description' => 'Another Description of my first Idea',
        ]);

        $response = $this->get(route('idea.show', $ideaOne));

        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'ideas/my-first-idea');

        $response = $this->get(route('idea.show', $ideaTwo));

        $response->assertSuccessful();
        $this->assertTrue(request()->path() === 'ideas/my-first-idea-2');
    }
}
