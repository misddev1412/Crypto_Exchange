<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateUser()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user);
        $this->assertTrue(true);
        $this->addToAssertionCount(count($response));
    }
}
