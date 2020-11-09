<?php

namespace Tests\Unit\Order;

use App\Models\Core\User;
use App\Models\Order\Order;
use App\Models\Wallet\Wallet;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class CreateOrderUnitTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateOrder()
    {
        $user = factory(User::class)->create();
        $this->assertNotEmpty($user);
    }
}
