<?php

namespace App\Controller;

use App\DTO\PaginationDTO;
use App\Entity\QCM;
use App\Repository\QCMRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route('/api/qcm', methods: ['POST', 'GET'])]
class QcmController extends AbstractController
{
    #[Route('/', name: 'qcm.index')]
    public function index(
        QCMRepository $qcmRepository, 
        #[MapQueryString]
        ?PaginationDTO $paginationDTO = null
        ): JsonResponse
    {
        $page = $paginationDTO?->page;
        $limit = 20;
        $qcms = $qcmRepository->paginateQcm($page, $limit);

        return $this->json($qcms, Response::HTTP_OK, [], [
            'groups' => ['qcm.index']
        ]);
    }

    #[Route('/create', name: 'qcm.create', methods: ['POST'])]
    public function create(
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['qcm.create']
            ]
        )]
        QCM $qcm,
        EntityManagerInterface $em
    ): JsonResponse
    {
        $qcm->setCreatedAt(new \DateTimeImmutable);
        $em->persist($qcm);
        $em->flush();
        return $this->json($qcm, Response::HTTP_CREATED);
    }

    #[Route('/{id}/update', name :'qcm.update', requirements: ['id' => Requirement::DIGITS], methods: ['PUT'])]
    public function update(
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['qcm.update']
            ]
        )]
        QCM $qcm,
        EntityManagerInterface $em,
        int $id
    ): JsonResponse
    {
        $em->flush();
        return $this->json([
            'message' => printf('QCM entity with id %d has been updated', [$id]),
            'QCM' => $qcm,
        ], Response::HTTP_OK);
    }

    #[Route('/{id}/delete', name: 'qcm.delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(
        #[MapRequestPayload(
            serializationContext: [
                'groups' => ['qcm.delete']
            ]
        )]
        QCM $qcm,
        EntityManagerInterface $em,
        int $id
    ): JsonResponse
    {
        $em->remove($qcm);
        $em->flush();
        return $this->json([
            'message' => printf('QCM entity with id %d has been delete', [$id]),
            'QCM' => $qcm,
        ], Response::HTTP_OK);
    }
}
