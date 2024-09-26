<?php

namespace App\Entity;

use App\Repository\QCMSessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QCMSessionRepository::class)]
class QCMSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'qcmSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $participant = null;

    #[ORM\ManyToOne(inversedBy: 'qcmSessions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QCM $qcm = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $realized_at = null;

    /**
     * @var Collection<int, QCMSessionChoice>
     */
    #[ORM\OneToMany(targetEntity: QCMSessionChoice::class, mappedBy: 'qcmSession')]
    private Collection $qcmSessionChoices;

    public function __construct()
    {
        $this->qcmSessionChoices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getParticipant(): ?User
    {
        return $this->participant;
    }

    public function setParticipant(?User $participant): static
    {
        $this->participant = $participant;

        return $this;
    }

    public function getQcm(): ?QCM
    {
        return $this->qcm;
    }

    public function setQcm(?QCM $qcm): static
    {
        $this->qcm = $qcm;

        return $this;
    }

    public function getRealizedAt(): ?\DateTimeImmutable
    {
        return $this->realized_at;
    }

    public function setRealizedAt(\DateTimeImmutable $realized_at): static
    {
        $this->realized_at = $realized_at;

        return $this;
    }

    /**
     * @return Collection<int, QCMSessionChoice>
     */
    public function getQCMSessionChoices(): Collection
    {
        return $this->qcmSessionChoices;
    }

    public function addQCMSessionChoice(QCMSessionChoice $qcmSessionChoice): static
    {
        if (!$this->qcmSessionChoices->contains($qcmSessionChoice)) {
            $this->qcmSessionChoices->add($qcmSessionChoice);
            $qcmSessionChoice->setQcmSession($this);
        }

        return $this;
    }

    public function removeQCMSessionChoice(QCMSessionChoice $qcmSessionChoice): static
    {
        if ($this->qcmSessionChoices->removeElement($qcmSessionChoice)) {
            // set the owning side to null (unless already changed)
            if ($qcmSessionChoice->getQcmSession() === $this) {
                $qcmSessionChoice->setQcmSession(null);
            }
        }

        return $this;
    }
}
