<?php
// TODO: Attribuer les groups aux champs correspondant aux routes de l'API

namespace App\Entity;

use App\Repository\QCMRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: QCMRepository::class)]
class QCM
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups('qcm.index')]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    #[Groups('qcm.index')]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 255)]
    #[Groups(
        'qcm.index', 
        'qcm.create', 
        'qcm.update'
    )]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(
        'qcm.index', 
        'qcm.create', 
        'qcm.update'
    )]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'qcms')]
    #[Groups(
        'qcm.index', 
        'qcm.create', 
        'qcm.update'
    )]
    private ?Question $questions = null;

    /**
     * @var Collection<int, QCMSession>
     */
    #[ORM\OneToMany(targetEntity: QCMSession::class, mappedBy: 'qcm')]
    private Collection $qcmSessions;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

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
}
