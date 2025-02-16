<?php

namespace Tests\Unit;


use App\Models\Merch;
use App\Models\User;
use App\Repositories\MerchRepository;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserInfoTest extends TestCase
{
    use DatabaseTransactions;

    public function test_get_info_about_non_existent_user()
    {
        $repo = new UserRepository();
        $this->assertNull($repo->getUserInfoById(0));
    }

    public function test_get_info_about_merch_transaction()
    {
        $userRepo = new UserRepository();
        $merchRepo = new MerchRepository();
        $user = User::factory()->create(['name' => 'userTest', 'coins' => 1000]);
        $firstMerch = Merch::factory()->create(['name' => 'firstMerch', 'price' => 100]);
        $secondMerch = Merch::factory()->create(['name' => 'secondMerch', 'price' => 200]);
        $merchRepo->buyMerchForUser($user->id, $firstMerch->name);
        $merchRepo->buyMerchForUser($user->id, $secondMerch->name);
        $merchRepo->buyMerchForUser($user->id, $firstMerch->name);
        $info = $userRepo->getUserInfoById($user->id);
        $this->assertEquals(600, $info['coins']);
        $this->assertEquals([], $info['coinHistory']['received']);
        $this->assertEquals([], $info['coinHistory']['sent']);
        $this->assertEquals(['type' => 'firstMerch', 'quantity' => 2 ], $info['inventory'][0]);
        $this->assertEquals(['type' => 'secondMerch', 'quantity' => 1 ], $info['inventory'][1]);
    }

    public function test_get_info_about_coin_transaction()
    {
        $repo = new UserRepository();
        $sender = User::factory()->create(['name' => 'sender', 'coins' => 1000]);
        $receiver = User::factory()->create(['name' => 'receiver', 'coins' => 1000]);
        $repo->sendCoinsBetweenUsers($sender->id, $receiver->name, 150);
        $repo->sendCoinsBetweenUsers($receiver->id, $sender->name, 100);
        $repo->sendCoinsBetweenUsers($sender->id, $receiver->name, 150);
        $info = $repo->getUserInfoById($sender->id);
        $this->assertEquals(800, $info['coins']);
        $this->assertEquals([['fromUser' => 'receiver', 'amount' => 100]], $info['coinHistory']['received']);
        $this->assertEquals([['toUser' => 'receiver', 'amount' => 300]], $info['coinHistory']['sent']);
    }

}
