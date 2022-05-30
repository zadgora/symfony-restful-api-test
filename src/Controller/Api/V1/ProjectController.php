<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProjectController extends AbstractApiController
{

    /**
     * @var PprojectRepository
     */
    private $projectRepository;

    /**
     * @var TaskRepository
     */
    private $taskRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     * @param TaskRepository $taskRepository
     * @param ProjectRepository $projectRepository
     * 
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        TaskRepository $taskRepository,
        ProjectRepository $projectRepository
    ) {
        $this->entityManager = $entityManager;
        $this->projectRepository = $projectRepository;
        $this->taskRepository = $taskRepository;
    }

    /**
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        try {
            $projects = $this->entityManager->createQuery(
                'SELECT p FROM App\Entity\Project p'
            )->getResult(Query::HYDRATE_ARRAY);
        } catch (\Exception $e) {
            $errors = [$e->getMessage()];
            return $this->errorResponse($errors);
        }

        return $this->json(['code' => JsonResponse::HTTP_OK, 'result' => $projects], JsonResponse::HTTP_OK);
    }

    /**
     * @param Request $request
     * 
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        try {
            $data = $request->request->all();
            $project = new Project();
            $form = $this->createForm(ProjectType::class, $project);
            $form->submit($data);

            if (!$form->isValid()) {
                $errors = $this->getErrorsFromForm($form);
                return $this->errorResponse($errors);
            }

            $this->entityManager->persist($project);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $errors = [$e->getMessage()];
            return $this->errorResponse($errors);
        }

        return $this->json(['code' => JsonResponse::HTTP_CREATED, 'message' => 'created'], JsonResponse::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param string $pid
     * 
     * @return JsonResponse
     */
    public function update(Request $request, string $pid): JsonResponse
    {
        try {
            $data = $request->request->all();
            $project = $this->projectRepository->find(['id' => $pid]);

            $form = $this->createForm(ProjectType::class, $project);
            $form->submit($data);

            if (!$form->isValid()) {
                $errors = $this->getErrorsFromForm($form);
                return $this->errorResponse($errors);
            }

            $this->entityManager->persist($project);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $errors = [$e->getMessage()];
            return $this->errorResponse($errors);
        }

        return $this->json(['code' => JsonResponse::HTTP_OK, 'message' => 'project updated'], JsonResponse::HTTP_OK);
    }

    /**
     * @param string $pid
     * 
     * @return JsonResponse
     */
    public function read(string $pid): JsonResponse
    {
        try {
            $project = $this->projectRepository->find(['id' => $pid]);
        } catch (\Exception $e) {
            $errors = [$e->getMessage()];
            return $this->errorResponse($errors);
        }
        return $this->json(['code' => JsonResponse::HTTP_OK, 'result' => $project], JsonResponse::HTTP_OK);
    }

    /**
     * @param string $pid
     * 
     * @return JsonResponse
     */
    public function delete(string $pid): JsonResponse
    {
        try {
            $project = $this->projectRepository->find(['id' => $pid]);

            if ($project && empty($project->getDeteletedAt())) {
                $project->setDeteletedAt(new \DateTime());

                $this->entityManager->persist($project);
                $this->entityManager->flush();

                return $this->json(['code' => JsonResponse::HTTP_OK, 'message' => 'project deleted'], JsonResponse::HTTP_OK);
            }
        } catch (\Exception $e) {
            $errors = [$e->getMessage()];
            return $this->errorResponse($errors);
        }

        return $this->json(['code' => JsonResponse::HTTP_NOT_FOUND, 'message' => 'project not found'], JsonResponse::HTTP_OK);
    }
}
