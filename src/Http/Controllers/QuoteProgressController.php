<?php

namespace Jauntin\SavingQuote\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Jauntin\SavingQuote\Http\Resources\QuoteResource;
use Jauntin\SavingQuote\Interfaces\QuoteProgressValidationRules;
use Jauntin\SavingQuote\Models\QuoteProgress;
use Jauntin\SavingQuote\Service\QuoteProgressService;
use Throwable;

class QuoteProgressController extends BaseController
{
    /**
     * @throws ValidationException
     */
    public function single(string $hash, QuoteProgressService $service, QuoteProgressValidationRules $validator): JsonResponse
    {
        $quoteProgress = QuoteProgress::find($hash);

        if (! $quoteProgress || $quoteProgress->expire_at < Carbon::now()) {
            return new JsonResponse('', 404);
        }

        try {
            $data = $validator->transformData($quoteProgress->data ?? []);
            Validator::make($data, $validator->rules($data))->validate();
        } catch (ValidationException $e) {
            throw $e;
        } catch (Throwable $e) {
            Log::info(sprintf('Unable to process saved quote %s. Message: %s', $hash, $e->getMessage()), ['trace' => $e->getTraceAsString()]);

            return new JsonResponse(['message' => 'Unable to process data'], 400);
        }

        return new JsonResponse((new QuoteResource($service->markAsOpened($quoteProgress)))->toArray(), 200);
    }

    public function create(Request $request, QuoteProgressService $service, QuoteProgressValidationRules $validator): JsonResponse
    {
        $request->merge($request->input('data')['formData'] ?? []);

        $request->validate(QuoteProgressService::rules($validator));
        $service->setData($request->toArray());
        $quoteProgress = $service->execute();

        return new JsonResponse((new QuoteResource($quoteProgress))->toArray(), 201);
    }
}
