<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Form\ReponseType;
use App\Repository\ReclamationRepository;
use App\Repository\ReponseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/reponse')]
final class ReponseController extends AbstractController
{
    // Affiche la liste de toutes les réponses
    #[Route('/list', name: 'app_reponse_list', methods: ['GET'])]
    public function list(ReponseRepository $reponseRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        // Récupérer les réponses associées aux réclamations de l'utilisateur
        $reponses = $reponseRepository->findByUserReclamations($user);

        return $this->render('reponse/list.html.twig', [
            'reponses' => $reponses,
        ]);
    }

    // Affiche une seule réponse
    #[Route('/{id}', name: 'app_reponse_show', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        // Recherche la réponse par son ID
        $reponse = $entityManager->getRepository(Reponse::class)->find($id);

        // Si la réponse n'existe pas, générer une exception
        if (!$reponse) {
            throw $this->createNotFoundException('La réponse n\'existe pas.');
        }

        return $this->render('reponse/show.html.twig', [
            'reponse' => $reponse,
        ]);
    }

    // Crée une nouvelle réponse
    #[Route('/new/{id}', name: 'app_reponse_new', methods: ['GET', 'POST'])]
    public function new(Reclamation $reclamation, Request $request, EntityManagerInterface $entityManager): Response
    {
        $reponse = new Reponse();
        $reponse->setReclamation($reclamation);

        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reponse);
            $entityManager->flush();

            $this->addFlash('success', 'Réponse envoyée avec succès.');
            return $this->redirectToRoute('app_reponse_list');
        }

        return $this->render('reponse/new.html.twig', [
            'form' => $form->createView(),
            'reclamation' => $reclamation,
        ]);
    }

    // Modifie une réponse existante
    #[Route('/reponse/{id}/edit', name: 'reponse_edit', methods: ['GET', 'POST'])]
    public function edit(int $id, ReponseRepository $reponseRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $reponse = $reponseRepository->find($id);

        if (!$reponse) {
            throw $this->createNotFoundException('La réponse à modifier n\'existe pas.');
        }

        $form = $this->createForm(ReponseType::class, $reponse);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_reponse_list');
        }

        return $this->render('reponse/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Supprime une réponse
    #[Route('/{id}', name: 'app_reponse_delete', methods: ['POST'])]
    public function delete(int $id, ReponseRepository $reponseRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $reponse = $reponseRepository->find($id);

        if ($reponse) {
            if ($this->isCsrfTokenValid('delete' . $reponse->getId(), $request->request->get('_token'))) {
                $entityManager->remove($reponse);
                $entityManager->flush();
            }
        }

        return $this->redirectToRoute('app_reponse_list');
    }


}
