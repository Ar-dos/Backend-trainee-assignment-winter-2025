<?php

namespace App\Http\Controllers;

use App\Interfaces\MerchRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BuyMerchItemController extends Controller
{
    private MerchRepositoryInterface $merchRepository;

    /**
     * Конструктор.
     *
     * @param MerchRepositoryInterface $merchRepository
     */
    public function __construct(MerchRepositoryInterface $merchRepository)
    {
        $this->merchRepository = $merchRepository;
    }

    /**
     * Обработать запрос на покупку товара.
     *
     * @param Request $request Запрос.
     * @param string  $item    Название товара.
     *
     * @return JsonResponse
     */
    public function __invoke(Request $request, string $item): JsonResponse
    {
        try {
            return $this->merchRepository->buyMerchForUser(Auth::id(), $item) ?
                response()->json(['message' => 'Успешный ответ.']) :
                response()->json(['message' => 'Неверный запрос.'], 400);

        } catch(ModelNotFoundException $e) {
            return response()->json(['message' => 'Неверный запрос.'], 400);
        }

    }
}
