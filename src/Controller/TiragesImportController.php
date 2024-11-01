<?php
namespace App\Controller;

use App\Entity\Tirages;
use App\Entity\Stats;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class TiragesImportController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
        $this->logger->debug("ds le construct de TiragesImportController");
    }

    private function toMonth($m) {
        $m = strtolower($m);
        $tbM = ['janvier' => '01',
                'fevrier' => '02',
                'mars' => '03',
                'avril' => '04',
                'mai' => '05',
                'juin' => '06',
                'juillet' => '07', 
                'août' => '08',
                'aout' => '08',
                'septembre' => '09',
                'octobre' => '10',
                'novembre' => '11',
                'décembre' =>'12'];
        if (array_key_exists($m, $tbM))
            return str_replace("û","u",$tbM[$m]);
        else {
            return null;
        }
    }

    public function __invoke(Request $request): JsonResponse
    {
        $this->logger->debug("ds le invole de TiragesImportController");
        $data = json_decode($request->getContent(), true);

        if (!isset($data['links']) || !is_array($data['links'])) {
            return new JsonResponse([
                'error' => 'Invalid parameter. Expected an array under the key "links".'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }

        $links = $data['links'];
        //$this->logger->debug("links=" . json_decode($links));
        foreach ($links as $link) {
            $ch = curl_init($link);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            $result = curl_exec($ch);
            if (curl_errno($ch)) {
                echo 'Erreur Curl: ' . curl_error($ch);
            }
            curl_close($ch);

            $filename = str_replace("û","u",basename($link));
            $lechemin =  "/tmp/";
            $this->logger->debug("ZZZZZZZ !!!!!!! le chemin sera  $lechemin  et puis $filename");
            file_put_contents($lechemin . $filename, $result);
            $filename_s1 = str_replace('.html','',$filename);
            $tbDtBrut = explode('-',str_replace(".htm","",$filename_s1));

            $lemois = $this->toMonth($tbDtBrut[6]);
            if ($lemois == null) {
                $this->logger->debug("!!!!!!!!!!!ERREUR ERREUR $lechemin.$filename");
            } else {
                $tir_date1 = $tbDtBrut[7] . "-" . $lemois . "-" . str_pad($tbDtBrut[5], 2,"0", STR_PAD_LEFT) . " 20:00:00";
                $tir_date2 = $tbDtBrut[7] . "-" . $lemois . "-" . str_pad($tbDtBrut[5], 2,"0", STR_PAD_LEFT) . " 21:00:00";

                // Utilisation de l'EntityManager pour récupérer le repository
                $tirageRepository = $this->entityManager->getRepository(Tirages::class);

                $existingTirage1 = $tirageRepository->findOneBy(['tir_date' => new \DateTime($tir_date1)]);
                $existingTirage2 = $tirageRepository->findOneBy(['tir_date' => new \DateTime($tir_date2)]);

                $dom = new \DOMDocument();
                $this->logger->debug("var charger $lechemin.$filename");
                $html = file_get_contents($lechemin . $filename);
                @$dom->loadHTML($html);

                $uls = $dom->getElementsByTagName('ul');
                foreach ($uls as $ul) {
                    $class = $ul->getAttribute('class');
                    if (strpos($class, 'tirage loto drawOrder') !== false) {
                        $loto1 = $ul;
                        $lis = $loto1->getElementsByTagName('li');

                        $curTir = ['tir_date' => $tir_date1,
                                    'tir_1' => '',
                                    'tir_2' => '',
                                    'tir_3' => '',
                                    'tir_4' => '',
                                    'tir_5' => '',
                                    'tir_c' => '0'];
                        $cc = 1;
                        foreach ($lis as $li) {
                            if ($cc < 6)
                                $curTir['tir_' . $cc] = $li->nodeValue;
                            else
                                $curTir['tir_c'] = $li->nodeValue;
                            $cc++;
                        }

                        $this->logger->debug("curTir1 : " . json_encode($curTir));
                        $tirage = new Tirages();
                        $tirage->setTirDate(new \DateTime($curTir['tir_date']));
                        $tirage->setTir1($curTir['tir_1']);
                        $tirage->setTir2($curTir['tir_2']);
                        $tirage->setTir3($curTir['tir_3']);
                        $tirage->setTir4($curTir['tir_4']);
                        $tirage->setTir5($curTir['tir_5']);
                        $tirage->setTirC($curTir['tir_c']);

                        // Persistance du tirage si inexistant
                        if (!$existingTirage1) {
                            $this->entityManager->persist($tirage);
                            $this->entityManager->flush();
                            //prepa des enrs stats
                            for ($c=1;$c<=6;$c++) {
                                if($c<6) {
                                   $stat1 = new Stats();
                                   $stat1->setStatDate(new \DateTime($curTir['tir_date']));
                                   $stat1->setStatNum($curTir["tir_" . $c]);
                                   $stat1->setStatC(0);
                                   $this->entityManager->persist($stat1);
                                   $this->entityManager->flush();
                                } else {
                                   $stat1 = new Stats();
                                   $stat1->setStatDate(new \DateTime($curTir['tir_date']));
                                   $stat1->setStatNum(0);
                                   $stat1->setStatC($curTir["tir_c"]);
                                   $this->entityManager->persist($stat1);
                                   $this->entityManager->flush(); 
                                }

                            }
                        }
                    }
                    //tirage 2
                    if (strpos($class, 'tirage second') !== false) {
                        $loto1 = $ul;
                        $lis = $loto1->getElementsByTagName('li');

                        $curTir = ['tir_date' => $tir_date2,
                                    'tir_1' => '',
                                    'tir_2' => '',
                                    'tir_3' => '',
                                    'tir_4' => '',
                                    'tir_5' => '',
                                    'tir_c' => '0'];
                        $cc = 1;
                        foreach ($lis as $li) {
                            if ($cc < 6)
                                $curTir['tir_' . $cc] = $li->nodeValue;
                            else
                                $curTir['tir_c'] = $li->nodeValue;
                            $cc++;
                        }

                        $this->logger->debug("curTir2 : " . json_encode($curTir));
                        $tirage = new Tirages();
                        $tirage->setTirDate(new \DateTime($curTir['tir_date']));
                        $tirage->setTir1($curTir['tir_1']);
                        $tirage->setTir2($curTir['tir_2']);
                        $tirage->setTir3($curTir['tir_3']);
                        $tirage->setTir4($curTir['tir_4']);
                        $tirage->setTir5($curTir['tir_5']);
                        $tirage->setTirC($curTir['tir_c']);

                        // Persistance du tirage si inexistant
                        if (!$existingTirage2) {
                            $this->entityManager->persist($tirage);
                            $this->entityManager->flush();
                            //prepa des enrs stats
                            for ($c=1;$c<=6;$c++) {
                                if($c<6) {
                                   $stat1 = new Stats();
                                   $stat1->setStatDate(new \DateTime($curTir['tir_date']));
                                   $stat1->setStatNum($curTir["tir_" . $c]);
                                   $stat1->setStatC(0);
                                   $this->entityManager->persist($stat1);
                                   $this->entityManager->flush();
                                } else {
                                   $stat1 = new Stats();
                                   $stat1->setStatDate(new \DateTime($curTir['tir_date']));
                                   $stat1->setStatNum(0);
                                   $stat1->setStatC($curTir["tir_c"]);
                                   $this->entityManager->persist($stat1);
                                   $this->entityManager->flush(); 
                                }

                            }
                        }
                    }

                    // Ajoutez ici la logique pour le second tirage, etc.
                }
            }
        }

        
        return new JsonResponse([
            'message' => 'Links processed successfully.',
            'processed_links' => $links
        ], JsonResponse::HTTP_OK);
    }
}
