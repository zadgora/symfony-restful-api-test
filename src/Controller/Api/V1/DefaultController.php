<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends AbstractApiController
{
    /**
     * Fallback 404 method
     * 
     * @return JsonResponse
     */
    public function fallback(): JsonResponse
    {
        $error = [
            'code' => JsonResponse::HTTP_NOT_FOUND,
            'error' => "Endpoint or method mismatch"
        ];
        return $this->json($error, JsonResponse::HTTP_OK);
    }
}
