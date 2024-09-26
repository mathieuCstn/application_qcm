<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    /**
     * @var Collection<int, QCM>
     */
    #[ORM\OneToMany(targetEntity: QCM::class, mappedBy: 'questions')]
    private Collection $qcms;

    /**
     * @var Collection<int, Choice>
     */
    #[ORM\OneToMany(targetEntity: Choice::class, mappedBy: 'question', orphanRemoval: true)]
    private Collection $choices;

    public function __construct()
    {
        $this->qcms = new ArrayCollection();
        $this->choices = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    /**
     * @return Collection<int, QCM>
     */
    public function getQcms(): Collection
    {
        return $this->qcms;
    }

    public function addQcm(QCM $qcm): static
    {
        if (!$this->qcms->contains($qcm)) {
            $this->qcms->add($qcm);
            $qcm->setQuestions($this);
        }

        return $this;
    }

    public function removeQcm(QCM $qcm): static
    {
        if ($this->qcms->removeElement($qcm)) {
            // set the owning side to null (unless already changed)
            if ($qcm->getQuestions() === $this) {
                $qcm->setQuestions(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Choice>
     */
    public function getChoices(): Collection
    {
        return $this->choices;
    }

    public function addChoice(Choice $choice): static
    {
        if (!$this->choices->contains($choice)) {
            $this->choices->add($choice);
            $choice->setQuestion($this);
        }

        return $this;
    }

    public function removeChoice(Choice $choice): static
    {
        if ($this->choices->removeElement($choice)) {
            // set the owning side to null (unless already changed)
            if ($choice->getQuestion() === $this) {
                $choice->setQuestion(null);
            }
        }

        return $this;
    }
}
