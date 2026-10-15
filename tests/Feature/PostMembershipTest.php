<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostMembershipTest extends TestCase
{
    use RefreshDatabase;

    public function test_free_member_cannot_create_public_post(): void
    {
        $user = User::factory()->create([
            'membership_level' => 'free',
        ]);

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'body' => 'Testing a restricted public post.',
            'audience' => 'public',
        ]);

        $response->assertSessionHasErrors('audience');

        $this->assertDatabaseMissing('posts', [
            'user_id' => $user->id,
            'body' => 'Testing a restricted public post.',
        ]);
    }


    public function test_free_member_cannot_create_family_post(): void
    {
        $user = User::factory()->create([
            'membership_level' => 'free',
        ]);

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'body' => 'Testing a restricted family post.',
            'audience' => 'family',
        ]);

        $response->assertSessionHasErrors('audience');

        $this->assertDatabaseMissing('posts', [
            'user_id' => $user->id,
            'body' => 'Testing a restricted family post.',
        ]);
    }



    public function test_free_member_cannot_publish_a_sixth_post_in_one_day(): void
    {
        $user = User::factory()->create([
            'membership_level' => 'free',
        ]);

        for ($i = 1; $i <= 5; $i++) {
            $user->posts()->create([
                'body' => 'Daily quota test post ' . $i,
                'audience' => 'only_me',
            ]);
        }

        $response = $this->actingAs($user)->post(route('posts.store'), [
            'body' => 'Daily quota test post six',
            'audience' => 'only_me',
        ]);

        $response->assertSessionHasErrors('body');

        $this->assertDatabaseMissing('posts', [
            'user_id' => $user->id,
            'body' => 'Daily quota test post six',
        ]);
    }



}
