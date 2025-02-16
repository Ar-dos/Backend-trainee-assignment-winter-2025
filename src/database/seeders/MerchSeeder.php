<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Merch as MerchEloquent;

class MerchSeeder extends Seeder
{
    /**
     * Способы.
     */
    private const ITEMS = [
        [
            'name' => 't-shirt',
            'price' => 80,
        ],
        [
            'name' => 'cup',
            'price' => 20,
        ],
        [
            'name' => 'book',
            'price' => 50,
        ],
        [
            'name' => 'pen',
            'price' => 10,
        ],
        [
            'name' => 'powerbank',
            'price' => 200,
        ],
        [
            'name' => 'hoody',
            'price' => 300,
        ],
        [
            'name' => 'umbrella',
            'price' => 200,
        ],
        [
            'name' => 'socks',
            'price' => 10,
        ],
        [
            'name' => 'wallet',
            'price' => 50,
        ],
        [
            'name' => 'pink-hoody',
            'price' => 500,
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(MerchEloquent $merchEloquent)
    {
        $merchEloquent
            ->newQuery()
            ->toBase()
            ->insert(self::ITEMS);
    }
}
