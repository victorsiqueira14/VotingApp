<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GravatarTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * */
    public function user_can_generate_gravatar_default_image_when_no_email_found_first_character_A()
    {
        $user = User::factory()->create([
            'name' => 'Victor',
            'email' => 'avictor@teste.com',
        ]);

        $gravatarUrl = $user->getAvatar();

        $this->assertEquals(
            'https://gravatar.com/avatar/'.md5($user->email).'?s=200&d=https://s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-1.png',
            $gravatarUrl);

        $response = Http::get($user->getAvatar());

        $this->assertTrue($response->successful());
    }

    /**
     * @test
     * */
    public function user_can_generate_gravatar_default_image_when_no_email_found_first_character_Z()
    {
        $user = User::factory()->create([
            'name' => 'Victor',
            'email' => 'Zvictor@teste.com',
        ]);

        $gravatarUrl = $user->getAvatar();

        $this->assertEquals(
            'https://gravatar.com/avatar/'.md5($user->email).'?s=200&d=https://s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-26.png',
            $gravatarUrl);

        $response = Http::get($user->getAvatar());

        $this->assertTrue($response->successful());
    }

    /**
     * @test
     * */
    public function user_can_generate_gravatar_default_image_when_no_email_found_first_character_9()
    {
        $user = User::factory()->create([
            'name' => 'Victor',
            'email' => '9victor@teste.com',
        ]);

        $gravatarUrl = $user->getAvatar();

        $this->assertEquals(
            'https://gravatar.com/avatar/'.md5($user->email).'?s=200&d=https://s3.amazonaws.com/laracasts/images/forum/avatars/default-avatar-36.png',
            $gravatarUrl);
    }
}
