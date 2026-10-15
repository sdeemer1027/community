<?php

namespace Tests\Feature;

use App\Models\Connection;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConnectionQuotaTest extends TestCase
{
    use RefreshDatabase;

    public function test_free_member_can_accept_a_connection_when_they_have_99(): void
    {
        $member = User::factory()->create([
            'membership_level' => 'free',
        ]);

        $limit = config('community.free_member_connection_limit', 100);
$this->createAcceptedConnections($member, $limit - 1);

        $requester = User::factory()->create();

        $pending = Connection::create([
            'requester_id' => $requester->id,
            'recipient_id' => $member->id,
            'status' => 'pending',
        ]);

        $this->actingAs($member)
            ->patch(route('connections.accept', $pending))
            ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('connections', [
            'id' => $pending->id,
            'status' => 'accepted',
        ]);

        $this->assertSame($limit, $this->acceptedCount($member));
    }

    public function test_free_member_cannot_accept_a_101st_connection(): void
    {
        $member = User::factory()->create([
            'membership_level' => 'free',
        ]);

        $limit = config('community.free_member_connection_limit', 100);
$this->createAcceptedConnections($member, $limit);

        $requester = User::factory()->create();

        $pending = Connection::create([
            'requester_id' => $requester->id,
            'recipient_id' => $member->id,
            'status' => 'pending',
        ]);

        $this->actingAs($member)
            ->patch(route('connections.accept', $pending))
            ->assertSessionHasErrors('connection');

        $this->assertDatabaseHas('connections', [
            'id' => $pending->id,
            'status' => 'pending',
        ]);

        $this->assertSame($limit, $this->acceptedCount($member));
    }

    public function test_requester_at_the_free_member_limit_cannot_be_accepted(): void
    {
        $requester = User::factory()->create([
            'membership_level' => 'free',
        ]);

        $limit = config('community.free_member_connection_limit', 100);
$this->createAcceptedConnections($requester, $limit);

        $recipient = User::factory()->create([
            'membership_level' => 'free',
        ]);

        $pending = Connection::create([
            'requester_id' => $requester->id,
            'recipient_id' => $recipient->id,
            'status' => 'pending',
        ]);

        $this->actingAs($recipient)
            ->patch(route('connections.accept', $pending))
            ->assertSessionHasErrors('connection');

        $this->assertDatabaseHas('connections', [
            'id' => $pending->id,
            'status' => 'pending',
        ]);

        $this->assertSame($limit, $this->acceptedCount($requester));
    }






    public function test_member_can_remove_an_accepted_connection(): void
    {
        $member = User::factory()->create([
            'membership_level' => 'free',
        ]);

        $otherUser = User::factory()->create();

        $connection = Connection::create([
            'requester_id' => $member->id,
            'recipient_id' => $otherUser->id,
            'status' => 'accepted',
        ]);

        $this->actingAs($member)
            ->delete(route('my-connections.destroy', $connection))
            ->assertRedirect(route('my-connections.index'))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('connections', [
            'id' => $connection->id,
        ]);

        $this->assertSame(0, $this->acceptedCount($member));
    }











    private function createAcceptedConnections(User $member, int $count): void
    {
        for ($i = 0; $i < $count; $i++) {
            $otherUser = User::factory()->create();

            Connection::create([
                'requester_id' => $member->id,
                'recipient_id' => $otherUser->id,
                'status' => 'accepted',
            ]);
        }
    }

    private function acceptedCount(User $member): int
    {
        return Connection::where('status', 'accepted')
            ->where(function ($query) use ($member) {
                $query->where('requester_id', $member->id)
                    ->orWhere('recipient_id', $member->id);
            })
            ->count();
    }
}
