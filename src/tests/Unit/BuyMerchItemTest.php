<?php

namespace Tests\Unit;

use App\Models\Merch;
use App\Models\MerchTransaction;
use App\Models\User;
use App\Repositories\MerchRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class BuyMerchItemTest extends TestCase
{
    use DatabaseTransactions;

    public function test_subtract_coins_from_user()
    {
        $user = User::factory()->create(['coins' => 1000]);
        $this->assertFalse($user->subtractCoins(1001));
        $this->assertEquals(1000, $user->coins);
        $this->assertFalse($user->subtractCoins(-1));
        $this->assertEquals(1000, $user->coins);
        $this->assertFalse($user->subtractCoins(0));
        $this->assertEquals(1000, $user->coins);
        $this->assertTrue($user->subtractCoins(500));
        $this->assertEquals(500, $user->coins);
        $this->assertTrue($user->subtractCoins(500));
        $this->assertEquals(0,$user->coins);
    }

    public function test_buy_non_existent_merch()
    {
        $this->expectException(ModelNotFoundException::class);
        $user = User::factory()->create();
        $repo = new MerchRepository();
        $repo->buyMerchForUser($user->id, 'non-existent-merch');
    }

    public function test_buy_merch_with_insufficient_funds()
    {
        $user = User::factory()->create(['coins' => 1000]);
        Merch::factory()->create(['name' => 'test', 'price' => 1001]);
        $repo = new MerchRepository();
        $result = $repo->buyMerchForUser($user->id, 'test');
        $user = $user->fresh();
        $this->assertFalse($result);
        $this->assertNull(MerchTransaction::where('user_id', $user->id)->first());
        $this->assertEquals(1000, $user->coins);
    }

    public function test_buy_merch_with_sufficient_funds()
    {
        $user = User::factory()->create(['coins' => 1000]);
        Merch::factory()->create(['name' => 'test', 'price' => 1000]);
        $repo = new MerchRepository();
        $result = $repo->buyMerchForUser($user->id, 'test');
        $user = $user->fresh();
        $this->assertTrue($result);
        $this->assertTrue(MerchTransaction::where('user_id', $user->id)->first() instanceof MerchTransaction);
        $this->assertEquals(0, $user->coins);
    }
}
