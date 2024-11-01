<?php
// src/Service/TirageService.php

namespace App\Service;
use App\Entity\Tirages;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class TirageService
{
    private $em;
    private $logger;

    public function __construct(EntityManagerInterface $em, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->logger = $logger;
    }

    public function getTirages(\DateTime $dateBase): array
    {
        // Construction de la requête principale
        $strSQL = "SELECT t FROM App\Entity\Tirages t WHERE t.tirDate <= :dateBase ORDER BY t.tirDate DESC";
        $query = $this->em->createQuery($strSQL)
                          ->setParameter('dateBase', $dateBase)
                          ->setMaxResults(80);
        $objs = $query->getResult();

        $tbAllDistances = [0]; // Initialisation
        $lastDate = $this->getLastTirageDate(); // Récupération de la dernière date de tirage

        for ($num = 1; $num <= 49; $num++) {
            $dernierTirageLe = $this->getDernierTiragePourNum($num, $lastDate);
            $distanceNum = $this->getDistanceTirage($dernierTirageLe, $lastDate);
            $tbAllDistances[] = ["distance" => $distanceNum, "dernier" => $dernierTirageLe];
        }

        // Calcul des distances pour chaque tirage
        $objsRetour = [];
        foreach ($objs as $obj) {
            $tbDistances = $this->calculerDistances($obj);
            $maxDistance = max($tbDistances);

            // Construction des résultats
            $objsRetour[] = [
                "TIR_DATE" => $obj->getTirDate(),
                "TIR_1" => $obj->getTir1(),
                "TIR_2" => $obj->getTir2(),
                "TIR_3" => $obj->getTir3(),
                "TIR_4" => $obj->getTir4(),
                "TIR_5" => $obj->getTir5(),
                "distances" => $tbDistances,
                "max_distance" => $maxDistance,
                "backgroundColor" => "#000000",
            ];
        }

        return [
            'datas' => $objsRetour,
            'all_distances' => $tbAllDistances,
            'date_base' => $dateBase,
        ];
    }

    private function getLastTirageDate()
    {
        $sql = "SELECT t.tirDate FROM App\Entity\Tirages t ORDER BY t.tirDate DESC";
        return $this->em->createQuery($sql)->setMaxResults(1)->getSingleScalarResult();
    }

    private function getDernierTiragePourNum(int $num, \DateTime $lastDate)
    {
        $sql = "SELECT t.tirDate FROM App\Entity\Tirages t 
                WHERE :num IN (t.tir1, t.tir2, t.tir3, t.tir4, t.tir5) 
                AND t.tirDate < :lastDate
                ORDER BY t.tirDate DESC";
        return $this->em->createQuery($sql)
                        ->setParameters(['num' => $num, 'lastDate' => $lastDate])
                        ->setMaxResults(1)
                        ->getSingleScalarResult();
    }

    private function getDistanceTirage($dernierTirageLe, $lastDate)
    {
        $sql = "SELECT COUNT(t) FROM App\Entity\Tirages t 
                WHERE t.tirDate BETWEEN :dernierTirageLe AND :lastDate";
        return $this->em->createQuery($sql)
                        ->setParameters(['dernierTirageLe' => $dernierTirageLe, 'lastDate' => $lastDate])
                        ->getSingleScalarResult();
    }

    private function calculerDistances($obj)
    {
        $tbDistances = [];
        for ($i = 1; $i <= 5; $i++) {
            $tir = "getTir" . $i;
            $sql = "SELECT t.tirDate FROM App\Entity\Tirages t 
                    WHERE :tirNum IN (t.tir1, t.tir2, t.tir3, t.tir4, t.tir5)
                    AND t.tirDate < :tirDate 
                    ORDER BY t.tirDate DESC";
            $dateTirage = $this->em->createQuery($sql)
                                   ->setParameters(['tirNum' => $obj->$tir(), 'tirDate' => $obj->getTirDate()])
                                   ->setMaxResults(1)
                                   ->getSingleScalarResult();

            $sql2 = "SELECT COUNT(t) FROM App\Entity\Tirages t 
                     WHERE t.tirDate BETWEEN :dateTirage AND :tirDate";
            $distance = $this->em->createQuery($sql2)
                                 ->setParameters(['dateTirage' => $dateTirage, 'tirDate' => $obj->getTirDate()])
                                 ->getSingleScalarResult();
            $tbDistances[] = $distance - 1;
        }

        return $tbDistances;
    }
}
