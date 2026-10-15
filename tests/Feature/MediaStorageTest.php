<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use App\Models\Post;

class MediaStorageTest extends TestCase
{
    use RefreshDatabase;

    public function test_free_member_cannot_exceed_configured_media_storage_limit(): void
    {
        Storage::fake('local');

        $user = User::factory()->create([
            'membership_level' => 'free',
        ]);

$storageLimitMb = config('community.free_member_storage_limit_mb', 100);
$storedSize = max($storageLimitMb - 5, 0) * 1024 * 1024;

// Simulate media already stored in the library.
$user->mediaItems()->create([
    'file_path' => $user->id . '/existing.jpg',
    'media_type' => 'image',
    'original_filename' => 'existing.jpg',
    'file_size' => $storedSize,
    'mime_type' => 'image/jpeg',
]);
        // Attempt to upload another 6 MB image.
        $file = UploadedFile::fake()
 ->create('extra.jpg', 6 * 1024, 'image/jpeg');
 //           ->image('extra.jpg')
 //           ->size(6 * 1024);

        $response = $this->actingAs($user)->post(
            route('media-library.store'),
            ['media' => $file]
        );

        $response->assertSessionHasErrors('media');

        $this->assertDatabaseCount('media_items', 1);

        $this->assertSame(
            $storedSize,
            (int) $user->mediaItems()->sum('file_size')
        );

        $this->assertSame(
            [],
            Storage::disk('local')->allFiles((string) $user->id)
        );
    }







    public function test_free_member_can_upload_media_within_storage_limit(): void
    {
        Storage::fake('local');

        $user = User::factory()->create([
            'membership_level' => 'free',
        ]);

        $file = UploadedFile::fake()
            ->create('photo.jpg', 1024, 'image/jpeg');

        $response = $this->actingAs($user)->post(
            route('media-library.store'),
            ['media' => $file]
        );

        $response->assertRedirect(route('media-library.index'));

        $this->assertDatabaseHas('media_items', [
            'user_id' => $user->id,
            'original_filename' => 'photo.jpg',
            'media_type' => 'image',
        ]);

        $this->assertSame(
            1,
            $user->mediaItems()->count()
        );
    }



    public function test_deleting_media_frees_storage_allowance(): void
    {
        Storage::fake('local');

        $user = User::factory()->create([
            'membership_level' => 'free',
        ]);

        $mediaItem = $user->mediaItems()->create([
            'file_path' => $user->id . '/photo.jpg',
            'media_type' => 'image',
            'original_filename' => 'photo.jpg',
            'file_size' => config('community.free_member_storage_limit_mb', 100) * 1024 * 1024,
            'mime_type' => 'image/jpeg',
        ]);

        Storage::disk('local')->put(
            $mediaItem->file_path,
            'test image content'
        );

        $this->assertSame(
        config('community.free_member_storage_limit_mb', 100) * 1024 * 1024,
        (int) $user->mediaItems()->sum('file_size')
        );

        $this->actingAs($user)
            ->delete(route('media-library.destroy', $mediaItem))
            ->assertRedirect(route('media-library.index'));

        $this->assertDatabaseMissing('media_items', [
            'id' => $mediaItem->id,
        ]);

        $this->assertSame(
            0,
            (int) $user->mediaItems()->sum('file_size')
        );

        $this->assertFalse(
            Storage::disk('local')->exists($mediaItem->file_path)
        );
    }


    public function test_deleting_media_removes_post_attachment_but_preserves_post(): void
    {
        Storage::fake('local');

        $user = User::factory()->create([
            'membership_level' => 'free',
        ]);

        $post = $user->posts()->create([
            'body' => 'This post should remain after its media is deleted.',
            'audience' => 'only_me',
        ]);

        $mediaItem = $user->mediaItems()->create([
            'file_path' => $user->id . '/attached-photo.jpg',
            'media_type' => 'image',
            'original_filename' => 'attached-photo.jpg',
            'file_size' => 1024,
            'mime_type' => 'image/jpeg',
        ]);

        Storage::disk('local')->put(
            $mediaItem->file_path,
            'test image content'
        );

        $post->mediaItems()->attach($mediaItem->id);

        $this->assertDatabaseHas('post_media', [
            'post_id' => $post->id,
            'media_item_id' => $mediaItem->id,
        ]);

        $this->actingAs($user)
            ->delete(route('media-library.destroy', $mediaItem))
            ->assertRedirect(route('media-library.index'));

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'body' => 'This post should remain after its media is deleted.',
        ]);

        $this->assertDatabaseMissing('post_media', [
            'post_id' => $post->id,
            'media_item_id' => $mediaItem->id,
        ]);
    }




}
