<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class QuestionController extends AbstractController
{
    #[Route('/question', name: 'app_question')]
    public function index(
        QuestionRepository $questionRepository,
        #[MapQueryString]
        ?PaginationDTO $paginationDTO = null
    ): JsonResponse
    {
        $page = $paginationDTO?->page;
        $limit = 20;
        // TODO: Créer la fonction paginateQuestion().
        $questions = $questionRepository->paginateQuestion($page, $limit);
        return $this->json($questions, Response::HTTP_OK);
    }
}
