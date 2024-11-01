<?php

namespace App\Entity;

use App\Repository\MestiragesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MestiragesRepository::class)]
class Mestirages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $mtir_date = null;

    #[ORM\Column]
    private ?int $mtir_1 = null;

    #[ORM\Column]
    private ?int $mtir_2 = null;

    #[ORM\Column]
    private ?int $mtir_3 = null;

    #[ORM\Column]
    private ?int $mtir_4 = null;

    #[ORM\Column]
    private ?int $mtir_5 = null;

    #[ORM\Column]
    private ?int $mtir_c = null;

    #[ORM\Column(nullable: true)]
    private ?int $mtir_gain = null;

    #[ORM\Column(nullable: true)]
    private ?int $mtir_cout = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMtirDate(): ?\DateTimeInterface
    {
        return $this->mtir_date;
    }

    public function setMtirDate(\DateTimeInterface $mtir_date): static
    {
        $this->mtir_date = $mtir_date;

        return $this;
    }

    public function getMtir1(): ?int
    {
        return $this->mtir_1;
    }

    public function setMtir1(int $mtir_1): static
    {
        $this->mtir_1 = $mtir_1;

        return $this;
    }

    public function getMtir2(): ?int
    {
        return $this->mtir_2;
    }

    public function setMtir2(int $mtir_2): static
    {
        $this->mtir_2 = $mtir_2;

        return $this;
    }

    public function getMtir3(): ?int
    {
        return $this->mtir_3;
    }

    public function setMtir3(int $mtir_3): static
    {
        $this->mtir_3 = $mtir_3;

        return $this;
    }

    public function getMtir4(): ?int
    {
        return $this->mtir_4;
    }

    public function setMtir4(int $mtir_4): static
    {
        $this->mtir_4 = $mtir_4;

        return $this;
    }

    public function getMtir5(): ?int
    {
        return $this->mtir_5;
    }

    public function setMtir5(int $mtir_5): static
    {
        $this->mtir_5 = $mtir_5;

        return $this;
    }

    public function getMtirC(): ?int
    {
        return $this->mtir_c;
    }

    public function setMtirC(int $mtir_c): static
    {
        $this->mtir_c = $mtir_c;

        return $this;
    }

    public function getMtirGain(): ?int
    {
        return $this->mtir_gain;
    }

    public function setMtirGain(?int $mtir_gain): static
    {
        $this->mtir_gain = $mtir_gain;

        return $this;
    }

    public function getMtirCout(): ?int
    {
        return $this->mtir_cout;
    }

    public function setMtirCout(?int $mtir_cout): static
    {
        $this->mtir_cout = $mtir_cout;

        return $this;
    }
}
