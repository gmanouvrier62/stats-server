<?php

namespace App\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\Tirages;
use App\Form\TiragesType;
use App\Repository\TiragesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/tirages')]
final class TiragesController extends AbstractController
{
    #[Route(name: 'app_tirages_index', methods: ['GET'])]
    public function index(TiragesRepository $tiragesRepository): Response
    {
        return $this->render('tirages/index.html.twig', [
            'tirages' => $tiragesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_tirages_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $tirage = new Tirages();
        $form = $this->createForm(TiragesType::class, $tirage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($tirage);
            $entityManager->flush();

            return $this->redirectToRoute('app_tirages_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tirages/new.html.twig', [
            'tirage' => $tirage,
            'form' => $form,
        ]);
    }
    #[Route('/gloup', name: 'app_tirages_gloup', methods: ['GET'])]
    public function gloup(): JsonResponse
    {
         return new JsonResponse([
            'message' => 'glouping.',
            'processed_links' => 'art de gloupper'
        ], JsonResponse::HTTP_OK);
    }
    #[Route('/{id}', name: 'app_tirages_show', methods: ['GET'])]
    public function show(Tirages $tirage): Response
    {
        return $this->render('tirages/show.html.twig', [
            'tirage' => $tirage,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_tirages_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tirages $tirage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TiragesType::class, $tirage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_tirages_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('tirages/edit.html.twig', [
            'tirage' => $tirage,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_tirages_delete', methods: ['POST'])]
    public function delete(Request $request, Tirages $tirage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$tirage->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($tirage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_tirages_index', [], Response::HTTP_SEE_OTHER);
    }
}
