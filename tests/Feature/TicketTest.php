<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;
use App\Models\Ticket;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_requires_auth()
    {
        $category = Category::factory()->create();

        $this->postJson('/api/tickets', [
            'category_id' => $category->id,
            'movie_title' => 'Foo Movie',
        ])->assertStatus(401);
    }

    public function test_create_update_delete_ticket()
    {
        $user = User::factory()->create();
        $category = Category::factory()->create();
        $token = auth('api')->login($user);

        // create
        $res = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->post('/api/tickets', [
                'category_id' => $category->id,
                'movie_title' => 'My Movie',
                'description' => 'Desc'
            ]);

        if ($res->status() !== 201) {
            // print response for debugging
            fwrite(STDERR, "CREATE RESPONSE: " . $res->getContent() . "\n");
        }

        $res->assertStatus(201)->assertJsonStructure(['message', 'data']);
        $ticketId = $res['data']['id'] ?? null;
        $this->assertNotNull($ticketId);

        $slug = $res['data']['slug'];

        // update
        $res2 = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->put('/api/tickets/' . $slug, [
                'movie_title' => 'Updated Title'
            ]);

        $res2->assertStatus(200)->assertJsonPath('data.movie_title', 'Updated Title');

        // delete
        $res3 = $this->withHeaders(['Authorization' => 'Bearer ' . $token])
            ->delete('/api/tickets/' . $slug);

        $res3->assertStatus(200);
        $this->assertDatabaseMissing('tickets', ['slug' => $slug]);
    }
}
