<?php

namespace App\Interfaces;

interface MerchRepositoryInterface
{

    /**
     * @param int $userId
     * @param string $merchName
     *
     * @return bool
     */
    public function buyMerchForUser(int $userId, string $merchName): bool;
}
