<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Enum\Statut;
use App\Repository\ReservationRepository;
use App\Repository\TrajetRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Trajet;

#[Route('/reservation')]
class ReservationController extends AbstractController
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // Liste des réservation



// Liste des réservations faites par l'utilisateur
#[Route('/my-reservations', name: 'my_reservations', methods: ['GET'])]
public function myReservations(ReservationRepository $reservationRepository): Response
{
    // Récupérer l'utilisateur connecté
    $user = $this->getUser();

    // Trouver toutes les réservations où l'utilisateur est le passager
    $reservations = $reservationRepository->findBy(['user' => $user]);

    // Convertir les statuts en instances d'énumération Statut
    foreach ($reservations as $reservation) {
        // Récupérer la valeur de l'énumération (chaîne ou int) et la convertir en instance de Statut
        $statut = Statut::from($reservation->getStatut()->value); // Utilisation de .value pour obtenir la valeur sous-jacente
        $reservation->setStatut($statut);
    }

    return $this->render('reservation/my_reservations.html.twig', [
        'reservations' => $reservations,
    ]);
}



// Liste des réservations effectuées sur les trajets de l'utilisateur (en tant que conducteur)
#[Route('/reservations-on-my-trips', name: 'reservations_on_my_trips', methods: ['GET'])]
public function reservationsOnMyTrips(ReservationRepository $reservationRepository, TrajetRepository $trajetRepository): Response
{
    // Récupérer l'utilisateur connecté
    $user = $this->getUser();

    // Trouver tous les trajets créés par l'utilisateur
    $trajets = $trajetRepository->findBy(['user' => $user]);

    // Récupérer toutes les réservations sur ces trajets
    $reservations = [];
    foreach ($trajets as $trajet) {
        $reservations = array_merge($reservations, $reservationRepository->findBy(['trajet' => $trajet]));
    }

    // Convertir les statuts en instances d'énumération si nécessaire
    foreach ($reservations as $reservation) {
        $reservation->setStatut(Statut::from($reservation->getStatut()->value));
    }

    return $this->render('reservation/reservations_on_my_trips.html.twig', [
        'reservations' => $reservations,
    ]);
}


    


    // Créer une nouvelle réservation
    #[Route('/new/{idTrajet}', name: 'new_reservation', methods: ['GET', 'POST'])]
    public function new(int $idTrajet, Request $request): Response
    {
        // Trouver le trajet en fonction de l'ID
        $trajet = $this->entityManager->getRepository(Trajet::class)->find($idTrajet);
        
        if (!$trajet) {
            throw $this->createNotFoundException('Trajet introuvable.');
        }
    
        // Vérifier s'il y a des places disponibles
        if ($trajet->getNombrePlaces() <= 0) {
            throw $this->createNotFoundException('Aucune place disponible pour ce trajet.');
        }
        
        $user = $this->getUser();
        $existingReservation = $this->entityManager->getRepository(Reservation::class)->findOneBy([
            'user' => $user,
            'trajet' => $trajet,
        ]);
    
        if ($existingReservation) {
            // Si une réservation existe déjà pour cet utilisateur et ce trajet
            $this->addFlash('error', 'Vous avez déjà réservé ce trajet.');
            return $this->redirectToRoute('my_reservations'); // rediriger l'utilisateur vers ses réservations
        }

        $reservation = new Reservation();
    
        // Récupérer l'utilisateur connecté
        $user = $this->getUser();
    

    
        // Associer l'utilisateur au trajet
        $reservation->setUser($user);
    
        // Construire le nom du conducteur à partir des propriétés de l'utilisateur
        $user = $reservation->getUser();

        // Vérifier si l'utilisateur existe avant d'accéder à ses propriétés

            // Récupérer le nom et le prénom de l'utilisateur
            $nomPassager = $user->getNom() . ' ' . $user->getPrenom();
            $reservation->setNomPassager($nomPassager);
        $reservation->setTrajet($trajet); // Associer le trajet
        $reservation->setUser($user); // Associer l'utilisateur connecté
        $reservation->setStatut(Statut::EN_ATTENTE); // Assuming EN_ATTENTE is the string constant in Statut enum
        $reservation->setNomPassager($user->getNom()); // Définir le nom du passager
    
        // Déduire une place disponible
//        $trajet->setNombrePlaces($trajet->getNombrePlaces() - 1);
    
        // Persister les données dans la base de données
        $this->entityManager->persist($reservation);
        $this->entityManager->persist($trajet); // Mettre à jour le trajet avec les places restantes
        $this->entityManager->flush();
        
        // Rediriger vers la liste des réservations
        return $this->redirectToRoute('my_reservations');
    }
    


    
    
    
    


    // Supprimer une réservation
    #[Route('/delete/{id}', name: 'delete_reservation', methods: ['POST'])]
    public function delete(Request $request, Reservation $reservation): Response
    {
        if ($this->isCsrfTokenValid('delete' . $reservation->getIdReservation(), $request->get('_token'))) {
            $this->entityManager->remove($reservation);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('my_reservations');
    }


// Accepter une réservation
    #[Route('/accept/{id}', name: 'accept_reservation', methods: ['POST'])]
    public function accept(Reservation $reservation): Response
    {
        // Vérifier si la réservation est déjà acceptée ou refusée
        if (in_array($reservation->getStatut(), [Statut::accepte, Statut::refuse])) {
            // Si oui, empêcher l'action et rediriger ou afficher un message
            return $this->redirectToRoute('my_reservations', [
                'message' => 'Cette réservation ne peut plus être modifiée.'
            ]);
        }

        // Si le statut n'est pas accepté ou refusé, procéder à l'acceptation
        $reservation->setStatut(Statut::accepte);

        // Récupérer le trajet lié à la réservation
        $trajet = $reservation->getTrajet();

        // Vérifier si le trajet a des places disponibles
        if ($trajet->getNombrePlaces() > 0) {
            // Décrémenter le nombre de places disponibles
            $trajet->setNombrePlaces($trajet->getNombrePlaces() - 1);

            // Sauvegarder les modifications
            $this->entityManager->flush();
        } else {
            // Si aucune place n'est disponible, afficher un message ou rediriger
            return $this->redirectToRoute('reservations_on_my_trips', [
                'message' => 'Il n\'y a plus de places disponibles sur ce trajet.'
            ]);
        }

        return $this->redirectToRoute('my_reservations');
    }


// Refuser une réservation
#[Route('/refuse/{id}', name: 'refuse_reservation', methods: ['POST'])]
public function refuse(Reservation $reservation): Response
{
    // Vérifier si la réservation est déjà acceptée ou refusée
    if (in_array($reservation->getStatut(), [Statut::accepte, Statut::refuse])) {
        // Si oui, empêcher l'action et rediriger ou afficher un message
        return $this->redirectToRoute('my_reservations', [
            'message' => 'Cette réservation ne peut plus être modifiée.'
        ]);
    }

    // Si le statut n'est pas accepté ou refusé, procéder au refus
    $reservation->setStatut(Statut::refuse);
    $this->entityManager->flush();

    return $this->redirectToRoute('my_reservations');
}


}