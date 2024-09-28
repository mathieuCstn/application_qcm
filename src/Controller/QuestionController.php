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
            'groups' => ['question.index', 'choice.index']
        ]);
    }

    #[Route('/create', name: 'question.create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['question.create', 'choice.create']
            ]
        )]
        Question $question,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $question
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
        ;
        $em->persist($question);
        $em->flush();
        return $this->json($question, Response::HTTP_CREATED, [], [
            'groups' => ['question.index', 'choice.index']
        ]);
    }

    #[Route('/{id}/update', name: 'question.update', requirements: ['id' => Requirement::DIGITS], methods: ['PUT'])]
    public function update(
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['question.update', 'choice.update']
            ]
        )]
        Question $question
    ): JsonResponse
    {
        return $this->json([
            'message' => printf('QCM entity with id %d has been updated', [$question->getId()]),
            'Question' => $question,
        ], Response::HTTP_OK, [], [
            'groups' => ['question.index', 'choice.index']
        ]);
    }

    #[Route('/{id}/delete', name: 'question.delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(
        Question $question,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $em->remove($question);
        $em->flush();
        return $this->json([
            'message' => printf('QCM entity with id %d has been deleted', [$question->getId()]),
            'QCM' => $question,
        ], Response::HTTP_OK, [], [
            'groups' => ['question.index', 'choice.index']
        ]);
    }
}
