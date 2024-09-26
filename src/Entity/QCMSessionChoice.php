<?php

namespace App\Entity;

use App\Repository\QCMSessionChoiceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QCMSessionChoiceRepository::class)]
class QCMSessionChoice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Choice $choice = null;

    #[ORM\ManyToOne(inversedBy: 'qcmSessionChoices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QCMSession $qcmSession = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChoice(): ?Choice
    {
        return $this->choice;
    }

    public function setChoice(?Choice $choice): static
    {
        $this->choice = $choice;

        return $this;
    }

    public function getQcmSession(): ?QCMSession
    {
        return $this->qcmSession;
    }

    public function setQcmSession(?QCMSession $qcmSession): static
    {
        $this->qcmSession = $qcmSession;

        return $this;
    }
}
