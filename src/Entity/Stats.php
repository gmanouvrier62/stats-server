<?php

namespace App\Entity;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use App\Repository\StatsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: StatsRepository::class)]
#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'stats:item']),
        new GetCollection(normalizationContext: ['groups' => 'tirages:list']),
        new Post(),
        new Patch(),
        new Put()        
    ],
    order: ['stat_date' => 'DESC'],
    paginationEnabled: 72,
)]

class Stats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $stat_date = null;

    #[ORM\Column]
    private ?int $stat_num = null;

    #[ORM\Column]
    private ?int $stat_c = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatDate(): ?\DateTimeInterface
    {
        return $this->stat_date;
    }

    public function setStatDate(\DateTimeInterface $stat_date): static
    {
        $this->stat_date = $stat_date;

        return $this;
    }

    public function getStatNum(): ?int
    {
        return $this->stat_num;
    }

    public function setStatNum(int $stat_num): static
    {
        $this->stat_num = $stat_num;

        return $this;
    }

    public function getStatC(): ?int
    {
        return $this->stat_c;
    }

    public function setStatC(int $stat_c): static
    {
        $this->stat_c = $stat_c;

        return $this;
    }
}
