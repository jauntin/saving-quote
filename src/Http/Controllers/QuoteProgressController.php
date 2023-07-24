<?php

namespace Jauntin\SavingQuote\Http\Controllers;

use Jauntin\SavingQuote\Http\Resources\QuoteResource;
use Jauntin\SavingQuote\Interfaces\QuoteProgressValidator;
use Jauntin\SavingQuote\Models\QuoteProgress;
use Jauntin\SavingQuote\Service\QuoteProgressService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;
use Illuminate\Routing\Controller as BaseController;

class QuoteProgressController extends BaseController
{
    public function __construct(private readonly ?QuoteProgressValidator $validator = null)
    {
    }

    public function single(string $hash, QuoteProgressService $service): JsonResponse
    {
        /** @var QuoteProgress $quoteProgress */
        $quoteProgress = QuoteProgress::whereId($hash)->first();

        if (!$quoteProgress || $quoteProgress->expire_at < Carbon::now()) {
            return new JsonResponse('', 404);
        }

        if (isset($this->validator)) {
            $data = $quoteProgress->data['formData'] ?? [];
            $this->validator->rules($data);
            if (!empty($errors = $this->validator->validate($data))) {
                return new JsonResponse($errors, 422);
            }
        }

        return new JsonResponse((new QuoteResource($service->markAsOpened($quoteProgress)))->toArray(), 200);
    }

    public function create(Request $request, QuoteProgressService $service): JsonResponse
    {
        $request->validate(QuoteProgressService::rules());
        $service->setData($request->toArray());
        $quoteProgress = $service->execute();

        return new JsonResponse((new QuoteResource($quoteProgress))->toArray(), 201);
    }
}
