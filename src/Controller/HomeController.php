<?php

namespace App\Controller;

use App\Form\ProfileType;
use Cassandra\Type\UserType;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
        #[Route('/', name: 'app_home_page')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/profile', name: 'app_profile')]
    public function profile(): Response
    {
        return $this->render('home/profile.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }


    #[Route("/profile/edit", name: "edit_profile")]

    public function edit(Request $request): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();

        if (!$user) {
            throw $this->createNotFoundException('Utilisateur non trouvé');
        }

        // Créer le formulaire d'édition du profil
        $form = $this->createForm(ProfileType::class, $user);

        // Traitement du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Sauvegarder les modifications dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            // Message flash de succès
            $this->addFlash('success', 'Profil mis à jour avec succès.');

            return $this->redirectToRoute('edit_profile');
        }

        // Rendre la vue avec le formulaire
        return $this->render('home/editProfil.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
