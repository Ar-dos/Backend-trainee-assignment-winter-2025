<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendCoinRequest;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class SendCoinController extends Controller
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
     * Отправить монеты другому пользователю.
     *
     * @param SendCoinRequest $request Запрос.
     *
     * @return JsonResponse
     */
    public function __invoke(SendCoinRequest $request): JsonResponse
    {
        $requestParams = $request->validated();
        try {
            return $this->userRepository->sendCoinsBetweenUsers(
                Auth::id(),
                $requestParams['toUser'],
                $requestParams['amount'],
            ) ?
                response()->json(['message' => 'Успешный ответ.']) :
                response()->json(['message' => 'Неверный запрос.'], 400);

        } catch(ModelNotFoundException $e) {
            return response()->json(['message' => 'Неверный запрос.'], 400);
        }
    }
}
