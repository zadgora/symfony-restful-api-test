<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class AbstractApiController extends AbstractFOSRestController
{

    /**
     * Get error message from FormType
     * @param FormInterface $form
     * 
     * @return array()
     */
    protected function getErrorsFromForm(FormInterface $form): array
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }
        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errors;
    }

    /**
     * Meka a JSON response
     * @param Array $errors
     * @param int $statusCode
     * 
     * @return JsonResponse
     */
    protected function errorResponse(
        array $errors,
        int $statusCode = JsonResponse::HTTP_BAD_REQUEST
    ): JsonResponse {
        return $this->json(['code' => $statusCode, 'message' => $errors], JsonResponse::HTTP_OK);
    }

    
}
