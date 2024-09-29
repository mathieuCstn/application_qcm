<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\Entity\Choice;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
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
        Request $request,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $requestContent = json_decode($request->getContent(), true);

        $question = new Question();
        $question
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setContent($requestContent['content'])
        ;

        foreach($requestContent['choices'] as $choiceData) {
            $choice = new Choice();
            $choice
                ->setChoiceContent($choiceData['choiceContent'])
                ->setCorrect($choiceData['isCorrect'])
                ->setFeedback($choiceData['feedback'])
            ;
            $em->persist($choice);
            $question->addChoice($choice);
        }
        $em->persist($question);
        $em->flush();
        return $this->json($question, Response::HTTP_CREATED, [], [
            'groups' => ['question.index', 'choice.index']
        ]);
    }

    #[Route('/{id}/update', name: 'question.update', requirements: ['id' => Requirement::DIGITS], methods: ['PUT'])]
public function update(
    int $id,
    Request $request,
    EntityManagerInterface $em,
    QuestionRepository $questionRepository
): JsonResponse
{
    $question = $questionRepository->find($id);
    
    if (!$question) {
        return $this->json([
            'error' => sprintf('No Question were found with id %d.', $id)
        ], Response::HTTP_NOT_FOUND);
    }

    $requestContent = json_decode($request->getContent(), true);

    if (isset($requestContent['content'])) {
        $question->setContent($requestContent['content']);
    }

    if (isset($requestContent['choices'])) {
        foreach ($question->getChoices() as $existingChoice) {
            $em->remove($existingChoice);
        }
        $question->getChoices()->clear();

        foreach ($requestContent['choices'] as $choiceData) {
            $choice = new Choice();
            $choice
                ->setChoiceContent($choiceData['choiceContent'])
                ->setCorrect($choiceData['isCorrect'])
                ->setFeedback($choiceData['feedback'] ?? null);
            
            $em->persist($choice);
            $question->addChoice($choice);
        }
    }

    $question->setUpdatedAt(new \DateTimeImmutable());

    $em->flush();

    return $this->json([
        'message' => sprintf('Question entity with id %d has been updated.', $question->getId()),
        'Question' => $question,
        ], Response::HTTP_OK, [], [
            'groups' => ['question.index', 'choice.index']
        ]);
    }

    #[Route('/{id}/delete', name: 'question.delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(
        int $id,
        EntityManagerInterface $em,
        QuestionRepository $questionRepository
    ): JsonResponse
    {
        $question = $questionRepository->find($id);
        if(!$question) {
            return $this->json([
                'error' => sprintf('No Question were found with id %d', $id)
            ], Response::HTTP_NOT_FOUND);
        }
        $em->remove($question);
        $em->flush();
        return $this->json([
            'message' => sprintf('Question entity with id %d has been deleted', $question->getId()),
            'question' => $question,
        ], Response::HTTP_OK, [], [
            'groups' => ['question.index', 'choice.index']
        ]);
    }
}
