<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\Entity\QCM;
use App\Repository\QCMRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/qcm', methods: ['POST', 'GET'])]
class QcmController extends AbstractController
{
    #[Route('/', name: 'qcm')]
    public function index(
        QCMRepository $qcmRepository, 
        #[MapQueryString]
        ?PaginationDTO $paginationDTO = null
        ): JsonResponse
    {
        $page = $paginationDTO?->page;
        $limit = 20;
        $qcms = $qcmRepository->paginateQcm($page, $limit);

        return $this->json($qcms, Response::HTTP_OK);
    }

    #[Route('/create', name: 'qcm.create')]
    public function create(
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['qcm.create']
            ]
        )]
        QCM $qcm
    ): JsonResponse
    {
        return $this->json([
            'message' => 'create qcm',
        ]);
    }

    #[Route('/{id}/update', name :'qcm.update', requirements: ['id' => Requirement::DIGITS])]
    public function update(QCM $qcm): JsonResponse
    {
        return $this->json([]);
    }

    #[Route('/{id}/delete', name: 'qcm.delete', requirements: ['id' => Requirement::DIGITS])]
    public function delete(QCM $qcm): JsonResponse
    {
        return $this->json([]);
    }
}
