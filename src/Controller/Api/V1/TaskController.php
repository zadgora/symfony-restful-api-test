<?php

declare(strict_types=1);

namespace App\Controller\Api\V1;

use App\Entity\Project;
use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class TaskController extends AbstractApiController
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
     * @param Project $project
     * 
     * @return JsonResponse
     */
    public function list(string $pid): JsonResponse
    {
        try {
            $tasks = $this->entityManager
                ->createQuery('SELECT t FROM App\Entity\Task t WHERE IDENTITY(t.project) = :pid and t.deletedAt is null')
                ->setParameter('pid', $pid)
                ->getResult(Query::HYDRATE_ARRAY);
        } catch (\Exception $e) {
            $errors = [$e->getMessage()];
            return $this->errorResponse($errors);
        }
        return $this->json(
            [
                'code' => JsonResponse::HTTP_OK,
                'result' => $tasks
            ],
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @param Request $request
     * @param string $pid
     * 
     * @return JsonResponse
     */
    public function create(Request $request, string $pid): JsonResponse
    {
        try {
            $data = $request->request->all();
            $task = new Task();
            $form = $this->createForm(TaskType::class, $task);
            $form->submit($data);

            if (!$form->isValid()) {
                $errors = $this->getErrorsFromForm($form);
                return $this->errorResponse($errors);
            }

            $task->setProject($project);
            $this->entityManager->persist($task);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $errors = [$e->getMessage()];
            return $this->errorResponse($errors);
        }

        return $this->json(['code' => JsonResponse::HTTP_CREATED, 'message' => 'task created'], JsonResponse::HTTP_OK);
    }

    /**
     * @param string $tid     
     * 
     * @return JsonResponse
     */
    public function read(string $tid): JsonResponse
    {
        try {
            $task = $this->taskRepository->find(['id' => $tid]);
            if (!empty($task) && empty($task->getDeletedAt())) {
                return $this->json(['code' => JsonResponse::HTTP_NOT_FOUND, 'message' => 'task not found'], JsonResponse::HTTP_OK);
            }
        } catch (\Exception $e) {
            $errors = [$e->getMessage()];
            return $this->errorResponse($errors);
        }

        return $this->json(['code' => JsonResponse::HTTP_OK, 'message' => $task], JsonResponse::HTTP_OK);
    }

    /**
     * @param Request $request
     * @param string $tid
     * 
     * @return JsonResponse
     */
    public function update(Request $request, string $tid): JsonResponse
    {
        try {
            $task = $this->taskRepository->find(['id' => $tid]);
            if (empty($task)) {
                return $this->json(['code' => JsonResponse::HTTP_NOT_FOUND, 'message' => 'task not found'], JsonResponse::HTTP_OK);
            }
            $data = $request->request->all();
            $options = ['allow_extra_fields' => false];
            $form = $this->createForm(TaskType::class, $task, $options);
            $form->submit($data);

            if (!$form->isValid()) {
                $errors = $this->getErrorsFromForm($form);
                return $this->errorResponse($errors);
            }

            $task->setName($data['name']);
            $this->entityManager->persist($task);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            $errors = [$e->getMessage()];
            return $this->errorResponse($errors);
        }

        return $this->json(['code' => JsonResponse::HTTP_OK, 'message' => 'task updated'], JsonResponse::HTTP_OK);
    }

    /**
     * @param string $tid
     * 
     * @return JsonResponse
     */
    public function delete(string $tid): JsonResponse
    {
        try {
            $task = $this->taskRepository->find(['id' => $tid]);

            if (!empty($task) && empty($task->getDeletedAt())) {
                $task->setDeletedAt(new \DateTime());
                $this->entityManager->persist($task);
                $this->entityManager->flush();
                
                return $this->json(['code' => JsonResponse::HTTP_GONE, 'message' => 'task deleted'], JsonResponse::HTTP_OK);
            }
        } catch (\Exception $e) {
            $errors = [$e->getMessage()];
            return $this->errorResponse($errors);
        }

        return $this->json(['code' => JsonResponse::HTTP_NOT_FOUND, 'message' => 'task not found'], JsonResponse::HTTP_OK);
    }
}
