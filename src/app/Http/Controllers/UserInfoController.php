<?php

namespace App\Http\Controllers;

use App\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserInfoController extends Controller
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
     * @param Request $request Запрос.
     *
     * @return mixed
     */
    public function __invoke(Request $request): JsonResponse
    {
        $info = $this->userRepository->getUserInfoById(Auth::id());
        return response()->json($info);
    }
}
