<?php

// src/Controller/TrajetController.php

namespace App\Controller;

use App\Entity\Trajet;
use App\Repository\TrajetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface; // Correct import pour TokenStorage

#[Route('/trajet')]  
class TrajetController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private TokenStorageInterface $tokenStorage; // Utilisation de TokenStorageInterface

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $tokenStorage) // Constructeur mis à jour
    {
        $this->entityManager = $entityManager;
        $this->tokenStorage = $tokenStorage; // Assignation du service TokenStorage
    }

    #[Route('/accueil', name: 'accueil_trajets')]
    public function accueil(): Response
    {
        return $this->render('trajet/accueil.html.twig');
    }

    // Liste des trajets de l'utilisateur connecté
    #[Route('/mes', name: 'list_trajets_user', methods: ['GET'])]
    public function listTrajetsByUser(TrajetRepository $trajetRepository): Response
    {
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
        
        // Récupérer les trajets de l'utilisateur connecté
        $trajets = $trajetRepository->findBy(['user' => $user]);

        return $this->render('trajet/list_user.html.twig', [
            'trajets' => $trajets,
        ]);
    }

    

    // Liste des trajets des autres utilisateurs
#[Route('/other', name: 'list_trajets_others', methods: ['GET'])]
public function listTrajetsByOthers(TrajetRepository $trajetRepository): Response
{
    // Récupérer l'utilisateur connecté
    $user = $this->getUser();
    
    // Vérifier si l'utilisateur est bien connecté
    if (!$user) {
        // Rediriger ou afficher une erreur si l'utilisateur n'est pas connecté
        return $this->redirectToRoute('login');
    }

    // Créer une requête pour récupérer tous les trajets sauf ceux de l'utilisateur connecté
    $query = $trajetRepository->createQueryBuilder('t')
        ->innerJoin('t.user', 'u') // Assurez-vous que la relation est correcte
        ->where('u != :user') // Exclure les trajets de l'utilisateur connecté
        ->setParameter('user', $user)
        ->orderBy('t.dateHeure', 'ASC') // Trie les trajets par date
        ->getQuery();

    $trajets = $query->getResult();

    // Vérifier si des trajets ont été trouvés
    if (empty($trajets)) {
        // Vous pouvez afficher un message si aucun trajet n'est trouvé
        return $this->render('trajet/list_others.html.twig', [
            'message' => 'Aucun trajet disponible.',
        ]);
    }

    // Rendre la vue avec les trajets récupérés
    return $this->render('trajet/list_others.html.twig', [
        'trajets' => $trajets,
    ]);
}

    
    
    

    #[Route('/new', name: 'new_trajet', methods: ['GET', 'POST'])]
    public function new(Request $request, TrajetRepository $trajetRepository): Response
    {
        $trajet = new Trajet();
    
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
    

    
        // Associer l'utilisateur au trajet
        $trajet->setUser($user);
    
        // Construire le nom du conducteur à partir des propriétés de l'utilisateur
        $user = $trajet->getUser();

        // Vérifier si l'utilisateur existe avant d'accéder à ses propriétés

            // Récupérer le nom et le prénom de l'utilisateur
            $nomConducteur = $user->getNom() . ' ' . $user->getPrenom();
            $trajet->setNomConducteur($nomConducteur);

    
        $form = $this->createFormBuilder($trajet)
            ->add('depart', TextType::class, ['label' => 'Lieu de départ'])
            ->add('destination', TextType::class, ['label' => 'Destination'])
            ->add('dateHeure', DateTimeType::class, ['label' => 'Date et heure'])
            ->add('nombrePlaces', NumberType::class, ['label' => 'Nombre de places'])
            ->add('contributionFrais', NumberType::class, ['label' => 'Contribution aux frais'])
            ->add('save', SubmitType::class, ['label' => 'Enregistrer'])
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $trajetRepository->save($trajet, true);
    
            return $this->redirectToRoute('list_trajets_user');
        }
    
        return $this->render('trajet/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
        #[Route('/edit/{id}', name: 'edit_trajet', methods: ['GET', 'POST'])]
    public function edit(int $id, Request $request, TrajetRepository $trajetRepository): Response
    {
        $trajet = $trajetRepository->find($id);

        if (!$trajet) {
            return $this->redirectToRoute('list_trajets_user');
        }

        $form = $this->createFormBuilder($trajet)
            ->add('depart', TextType::class, ['label' => 'Lieu de départ'])
            ->add('destination', TextType::class, ['label' => 'Destination'])
            ->add('dateHeure', DateTimeType::class, ['label' => 'Date et heure'])
            ->add('nombrePlaces', NumberType::class, ['label' => 'Nombre de places'])
            ->add('contributionFrais', NumberType::class, ['label' => 'Contribution aux frais'])
            ->add('save', SubmitType::class, ['label' => 'Modifier'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trajetRepository->save($trajet, true);

            return $this->redirectToRoute('list_trajets_user');
        }

        return $this->render('trajet/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/trajet/delete/{id}', name: 'delete_trajet', methods: ['POST'])]
    public function delete(Request $request, Trajet $trajet)
    {
        if ($this->isCsrfTokenValid('delete'.$trajet->getIdTrajet(), $request->get('_token'))) {
            $this->entityManager->remove($trajet);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('list_trajets_user');
    }

    #[Route('/admin', name: 'display_admin')]
    public function indexAdmin(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
