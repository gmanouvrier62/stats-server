<?php

namespace App\Entity;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use App\Repository\TiragesRepository;
use App\Controller\TiragesImportController;
//use App\Controller\TiragesGetTiragesController;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: TiragesRepository::class)]
#[UniqueEntity(fields: ['tir_date'], message: 'Un tirage existe déjà pour cette date.')]

#[ApiResource(
    operations: [
        new Get(normalizationContext: ['groups' => 'tirages:item']),
        new GetCollection(normalizationContext: ['groups' => 'tirages:list']),
        new Post(),
        new Patch(),
        new Put(),
        new Post(
            uriTemplate: '/tiragess/import',
            controller: TiragesImportController::class, // Spécifier le contrôleur
            name: 'tirages_import',
            denormalizationContext: ['groups' => 'tirages:import'],
            openapiContext: [
                'summary' => 'Import des tirages',
                'description' => 'Importer plusieurs tirages via un fichier ou un payload personnalisé',
            ]
        ),
  
  //attention aux nommages...ici ce sera https://localhost:8000/tirages/gettirages  (sans api et sans ss a tirages)
    ],
    order: ['tir_date' => 'DESC'],
    paginationEnabled: 72,
)]
class Tirages
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['tirages:list', 'tirages:item'])]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['tirages:list', 'tirages:item'])]
    private ?\DateTimeInterface $tir_date = null;

    #[ORM\Column]
    #[Groups(['tirages:list', 'tirages:item'])]
    private ?int $tir_1 = null;

    #[ORM\Column]
    #[Groups(['tirages:list', 'tirages:item'])]
    private ?int $tir_2 = null;

    #[ORM\Column]
    #[Groups(['tirages:list', 'tirages:item'])]
    private ?int $tir_3 = null;

    #[ORM\Column]
    #[Groups(['tirages:list', 'tirages:item'])]
    private ?int $tir_4 = null;

    #[ORM\Column]
    #[Groups(['tirages:list', 'tirages:item'])]
    private ?int $tir_5 = null;

    #[ORM\Column]
    #[Groups(['tirages:list', 'tirages:item'])]
    private ?int $tir_c = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTirDate(): ?\DateTimeInterface
    {
        return $this->tir_date;
    }

    public function setTirDate(\DateTimeInterface $tir_date): static
    {
        $this->tir_date = $tir_date;

        return $this;
    }

    public function getTir1(): ?int
    {
        return $this->tir_1;
    }

    public function setTir1(int $tir_1): static
    {
        $this->tir_1 = $tir_1;

        return $this;
    }

    public function getTir2(): ?int
    {
        return $this->tir_2;
    }

    public function setTir2(int $tir_2): static
    {
        $this->tir_2 = $tir_2;

        return $this;
    }

    public function getTir3(): ?int
    {
        return $this->tir_3;
    }

    public function setTir3(int $tir_3): static
    {
        $this->tir_3 = $tir_3;

        return $this;
    }

    public function getTir4(): ?int
    {
        return $this->tir_4;
    }

    public function setTir4(int $tir_4): static
    {
        $this->tir_4 = $tir_4;

        return $this;
    }

    public function getTir5(): ?int
    {
        return $this->tir_5;
    }

    public function setTir5(int $tir_5): static
    {
        $this->tir_5 = $tir_5;

        return $this;
    }

    public function getTirC(): ?int
    {
        return $this->tir_c;
    }

    public function setTirC(int $tir_c): static
    {
        $this->tir_c = $tir_c;

        return $this;
    }
}
