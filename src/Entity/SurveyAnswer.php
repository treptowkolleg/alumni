<?php

namespace App\Entity;

use App\Repository\SurveyAnswerRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SurveyAnswerRepository::class)]
class SurveyAnswer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?bool $answer = null;

    #[ORM\ManyToOne(inversedBy: 'surveyAnswers')]
    private ?Survey $survey = null;

    #[ORM\ManyToOne(inversedBy: 'surveyAnswers')]
    private ?User $user = null;

    public function __toString(): string
    {
        return $this->answer ? "ja" : "nein";
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function isAnswer(): ?bool
    {
        return $this->answer;
    }

    public function setAnswer(bool $answer): static
    {
        $this->answer = $answer;

        return $this;
    }

    public function getSurvey(): ?Survey
    {
        return $this->survey;
    }

    public function setSurvey(?Survey $survey): static
    {
        $this->survey = $survey;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
