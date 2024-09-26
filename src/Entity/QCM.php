<?php

namespace App\Entity;

use App\Repository\QCMRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QCMRepository::class)]
class QCM
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'qcms')]
    private ?Question $questions = null;

    /**
     * @var Collection<int, QCMSession>
     */
    #[ORM\OneToMany(targetEntity: QCMSession::class, mappedBy: 'qcm')]
    private Collection $qcmSessions;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    public function __construct()
    {
        $this->qcmSessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getQuestions(): ?Question
    {
        return $this->questions;
    }

    public function setQuestions(?Question $questions): static
    {
        $this->questions = $questions;

        return $this;
    }

    /**
     * @return Collection<int, QCMSession>
     */
    public function getQcmSessions(): Collection
    {
        return $this->qcmSessions;
    }

    public function addQcmSession(QCMSession $qcmSession): static
    {
        if (!$this->qcmSessions->contains($qcmSession)) {
            $this->qcmSessions->add($qcmSession);
            $qcmSession->setQcm($this);
        }

        return $this;
    }

    public function removeQcmSession(QCMSession $qcmSession): static
    {
        if ($this->qcmSessions->removeElement($qcmSession)) {
            // set the owning side to null (unless already changed)
            if ($qcmSession->getQcm() === $this) {
                $qcmSession->setQcm(null);
            }
        }

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }
}
