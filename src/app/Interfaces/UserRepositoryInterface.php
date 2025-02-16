<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    /**
     * @param string $username
     * @return int|null
     */
    public function getUserIdByUsername(string $username): ?int;

    public function authenticateUserByParams(string $username, string $password): ?string;
    public function registerUserByParams(string $username, string $password): string;

    public function getUserInfoById(int $id): ?array;

    public function sendCoinsBetweenUsers(int $senderId, string $receiverName, int $amount): bool;
}
