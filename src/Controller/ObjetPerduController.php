<?php

namespace App\Controller;

use App\Entity\ObjetPerdu;
use App\Form\ObjetPerduType;
use App\Repository\ObjetPerduRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/objetPerdu')]
final class ObjetPerduController extends AbstractController
{
    #[Route('/', name: 'app_objet_perdu_index', methods: ['GET', 'POST'])]
    public function index(Request $request, ObjetPerduRepository $objetPerduRepository): Response
    {
        // Création d'un formulaire pour filtrer les réclamations (si nécessaire)
        $form = $this->createForm(ObjetPerduType::class); // ObjetPerduFilterType est un exemple
        $form->handleRequest($request);

        // Récupération des objets perdus, en filtrant si le formulaire est soumis
        $objetPerdus = $objetPerduRepository->findAll(); // ou un filtrage selon la demande

        return $this->render('objet_perdu/index.html.twig', [
            'form' => $form->createView(),  // Passez le formulaire à la vue
            'objet_perdus' => $objetPerdus,
        ]);
    }


    #[Route('/new', name: 'app_objet_perdu_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $objetPerdu = new ObjetPerdu();
        $form = $this->createForm(ObjetPerduType::class, $objetPerdu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($objetPerdu);
            $entityManager->flush();

            return $this->redirectToRoute('app_objet_perdu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('objet_perdu/new.html.twig', [
            'objet_perdu' => $objetPerdu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_objet_perdu_show', methods: ['GET'])]
    public function show(ObjetPerdu $objetPerdu): Response
    {
        return $this->render('objet_perdu/show.html.twig', [
            'objet_perdu' => $objetPerdu,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_objet_perdu_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ObjetPerdu $objetPerdu, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ObjetPerduType::class, $objetPerdu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_objet_perdu_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('objet_perdu/edit.html.twig', [
            'objet_perdu' => $objetPerdu,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_objet_perdu_delete', methods: ['POST'])]
    public function delete(Request $request, ObjetPerdu $objetPerdu, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$objetPerdu->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($objetPerdu);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_objet_perdu_index', [], Response::HTTP_SEE_OTHER);
    }
}
