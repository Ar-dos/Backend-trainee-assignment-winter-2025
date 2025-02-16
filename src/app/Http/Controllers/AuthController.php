<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    private UserRepositoryInterface $userRepository;

    /**
     * Конструктор.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Обработать запрос на покупку товара.
     *
     * @param AuthRequest $request Запрос.
     *
     * @return mixed
     */
    public function __invoke(AuthRequest $request): JsonResponse
    {
        $userParams = $request->validated();
        $userId = $this->userRepository->getUserIdByUsername($userParams['username']);
        if ($userId) {
            $token = $this->userRepository->authenticateUserByParams(...$userParams);
        } else {
            $token = $this->userRepository->registerUserByParams(...$userParams);
        }
        return response()->json(['token' => $token]);
    }
}
