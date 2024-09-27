<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/question')]
class QuestionController extends AbstractController
{
    #[Route('/', name: 'question.index', methods: ['GET', 'POST'])]
    public function index(
        QuestionRepository $questionRepository,
        #[MapQueryString]
        ?PaginationDTO $paginationDTO = null
    ): JsonResponse
    {
        $page = $paginationDTO?->page;
        $limit = 20;
        $questions = $questionRepository->paginateQuestion($page, $limit);
        return $this->json($questions, Response::HTTP_OK, [], [
            'groups' => ['question.index']
        ]);
    }

    #[Route('/create', name: 'question.create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['question.create']
            ]
        )]
        Question $question,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $question;
        return $this->json($question, Response::HTTP_CREATED);
    }

    #[Route('/{id}/update', name: 'question.create', requirements: ['id' => Requirement::DIGITS], methods: ['PUT'])]
    public function update(
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['question.create']
            ]
        )]
        Question $question
    ): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/{id}/delete', name: 'question.create', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['question.create']
            ]
        )]
        Question $question
    ): JsonResponse
    {
        return $this->json([]);
    }
}
