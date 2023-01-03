<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Idea;
use App\Models\Category;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowIdeasTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Test if ideas shows on main page
     *
     * @return void
     */
    public function test_lest_of_ideas_shows_on_main_page()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $categoryTwo = Category::factory()->create(['name' => 'Category 2']);

        $ideaOne  = Idea::factory()->create([
            'title' => 'my first Idea',
            'category_id' => $categoryOne->id,
            'description' => 'Description of my first Idea',
        ]);

        $ideaTwo  = Idea::factory()->create([
            'title' => 'my second Idea',
            'category_id' => $categoryTwo->id,
            'description' => 'Description of my second Idea',
        ]);

        $response = $this->get(route('idea.index'));

        $response->assertSuccessful();
        $response->assertSee($ideaOne->title);
        $response->assertSee($ideaOne->Description);
        $response->assertSee($categoryOne->name);
        $response->assertSee($ideaTwo->title);
        $response->assertSee($ideaTwo->Description);
        $response->assertSee($categoryTwo->name);

    }

    /**
     * Test a single idea shows correcly on the show page
     *
     * @return void
     */
    public function test_a_sigle_idea_show_correcly_on_the_show_page()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);

        $idea  = Idea::factory()->create([
            'category_id' => $categoryOne->id,
            'title' => 'my first Idea',
            'description' => 'Description of my first Idea',
        ]);

        $response = $this->get(route('idea.index', $idea));

        $response->assertSuccessful();
        $response->assertSee($idea->title);
        $response->assertSee($idea->Description);
        $response->assertSee($categoryOne->name);
    }

    /**
     * test if ideas pagination works
     *
     * @return void
     */
    public function test_if_ideas_pagination_works()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        Idea::factory(Idea::PAGINATION_COUNT + 1)->create([
            'category_id' => $categoryOne->id,
        ]);

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

    /**
     * Test if the same idea is a title different slugs
     *
     * @return void
     */
    public function test_if_same_idea_title_different_slugs()
    {
        $categoryOne = Category::factory()->create(['name' => 'Category 1']);
        $ideaOne  = Idea::factory()->create([
            'category_id' => $categoryOne->id,
            'title' => 'my first idea',
            'description' => 'Description of my first Idea',
        ]);

        $ideaTwo  = Idea::factory()->create([
            'category_id' => $categoryOne->id,
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
