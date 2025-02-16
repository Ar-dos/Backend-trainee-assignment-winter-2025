<?php

namespace Tests\Unit;

use App\Models\CoinTransaction;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SendCoinTest extends TestCase
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

    public function test_add_coins_to_user()
    {
        $user = User::factory()->create(['coins' => 1000]);
        $this->assertFalse($user->addCoins(-1));
        $this->assertEquals(1000, $user->coins);
        $this->assertFalse($user->addCoins(0));
        $this->assertEquals(1000, $user->coins);
        $this->assertTrue($user->addCoins(500));
        $this->assertEquals(1500, $user->coins);
    }

    public function test_send_coins_from_non_existent_user()
    {
        $this->expectException(ModelNotFoundException::class);
        $receiver = User::factory()->create(['name' => 'receiver']);
        $repo = new UserRepository();
        $repo->sendCoinsBetweenUsers(0, $receiver->name, 100);
    }

    public function test_send_coins_to_non_existent_user()
    {
        $this->expectException(ModelNotFoundException::class);
        $sender = User::factory()->create(['name' => 'sender']);
        $repo = new UserRepository();
        $repo->sendCoinsBetweenUsers($sender->id, 'test', 100);
    }

    public function test_send_coins_with_insufficient_funds()
    {
        $sender = User::factory()->create(['name' => 'sender', 'coins' => 100]);
        $receiver = User::factory()->create(['name' => 'receiver', 'coins' => 0]);
        $repo = new UserRepository();
        $result = $repo->sendCoinsBetweenUsers($sender->id, $receiver->name, 101);
        $sender = $sender->refresh();
        $receiver = $receiver->refresh();
        $this->assertNull(CoinTransaction::where('sender_id', $sender->id)->first());
        $this->assertNull(CoinTransaction::where('receiver_id', $receiver->id)->first());
        $this->assertEquals(100, $sender->coins);
        $this->assertEquals(0, $receiver->coins);
        $this->assertFalse($result);
    }

    public function test_send_coins_with_sufficient_funds()
    {
        $sender = User::factory()->create(['name' => 'sender', 'coins' => 100]);
        $receiver = User::factory()->create(['name' => 'receiver', 'coins' => 0]);
        $repo = new UserRepository();
        $result = $repo->sendCoinsBetweenUsers($sender->id, $receiver->name, 100);
        $sender = $sender->refresh();
        $receiver = $receiver->refresh();
        $this->assertTrue(CoinTransaction::where('sender_id', $sender->id)->first() instanceof CoinTransaction);
        $this->assertTrue(CoinTransaction::where('receiver_id', $receiver->id)->first() instanceof CoinTransaction);
        $this->assertEquals(0, $sender->coins);
        $this->assertEquals(100, $receiver->coins);
        $this->assertTrue($result);
    }
}
