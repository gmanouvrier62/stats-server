<?php

namespace App\Controller;

use App\Entity\Stats;
use App\Form\StatsType;
use App\Repository\StatsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/stats')]
final class StatsController extends AbstractController
{
    #[Route(name: 'app_stats_index', methods: ['GET'])]
    public function index(StatsRepository $statsRepository): Response
    {
        return $this->render('stats/index.html.twig', [
            'stats' => $statsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_stats_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $stat = new Stats();
        $form = $this->createForm(StatsType::class, $stat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($stat);
            $entityManager->flush();

            return $this->redirectToRoute('app_stats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stats/new.html.twig', [
            'stat' => $stat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stats_show', methods: ['GET'])]
    public function show(Stats $stat): Response
    {
        return $this->render('stats/show.html.twig', [
            'stat' => $stat,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_stats_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Stats $stat, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StatsType::class, $stat);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_stats_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('stats/edit.html.twig', [
            'stat' => $stat,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_stats_delete', methods: ['POST'])]
    public function delete(Request $request, Stats $stat, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stat->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($stat);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_stats_index', [], Response::HTTP_SEE_OTHER);
    }
}
