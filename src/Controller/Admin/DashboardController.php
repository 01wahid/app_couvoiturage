<?php

namespace App\Controller\Admin;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use App\Repository\ReclamationRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{

    private $adminUrlGenerator;
    private $reclamationRepository;

    // Injection du repository via le constructeur
    public function __construct(AdminUrlGenerator $adminUrlGenerator, ReclamationRepository $reclamationRepository)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
        $this->reclamationRepository = $reclamationRepository;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Récupérer les statistiques des réclamations
        $statistics = $this->getStatistics();

        // Retourner la vue du tableau de bord avec les statistiques
        return $this->render('admin/dashboard.html.twig', [
            'statistics' => $statistics,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration')
            ->renderContentMaximized(); // Maximise l'affichage du contenu
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de Bord', 'fa fa-home');
        yield MenuItem::linkToCrud('Réclamations', 'fa fa-envelope', Reclamation::class);
        yield MenuItem::linkToCrud('Reponses', 'fa fa-envelope', Reponse::class);
        yield MenuItem::section('Autres');
        yield MenuItem::linkToLogout('Déconnexion', 'fa fa-sign-out-alt');
    }



    private function calculatePercentage(int $count, int $total): float
    {
        return $total > 0 ? round(($count / $total) * 100, 2) : 0;
    }


    // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        // return $this->render('some/path/my-dashboard.html.twig');



        #[Route('/dashboard', name:'dash')]
        public function Dashboard(): Response
        {
            return $this->render('/admin/main.html.twig');
        }

    #[Route('/dashboardReclamation', name:'dash_reclamation')]
    public function DashboardReclamation(ReclamationRepository $reclamationRepository): Response
    {
        $reclamations = $reclamationRepository->findAll();
        return $this->render('admin/reclamationDash.html.twig', [
            'reclamations' => $reclamations,
        ]);    }

    #[Route('/dashboardReponse', name:'dash_reponse')]
    public function DashboardReponse(ReclamationRepository $reponseRepository): Response
    {
        $reponses = $reponseRepository->findAll();
        return $this->render('admin/base.html.twig', [
            'reponses' => $reponses,
        ]);
    }

    // Fonction pour récupérer les statistiques des réclamations
    private function getStatistics(): array
    {
        $totalReclamations = $this->reclamationRepository->count([]);
        $urgentCount = $this->reclamationRepository->count(['priorite' => 'Urgent']);
        $moyenCount = $this->reclamationRepository->count(['priorite' => 'Moyen']);
        $basCount = $this->reclamationRepository->count(['priorite' => 'Bas']);

        $problèmesPaiementCount = $this->reclamationRepository->count(['categorie' => 'Problèmes de paiement']);
        $conduiteInappropriéeCount = $this->reclamationRepository->count(['categorie' => 'Conduite inappropriée']);
        $passagerNonPonctuelCount = $this->reclamationRepository->count(['categorie' => 'Passager non ponctuel']);
        $autresCount = $this->reclamationRepository->count(['categorie' => 'Autres']);

        return [
            'totalReclamations' => $totalReclamations,
            'priorities' => [
                'urgent' => $this->calculatePercentage($urgentCount, $totalReclamations),
                'moyen' => $this->calculatePercentage($moyenCount, $totalReclamations),
                'bas' => $this->calculatePercentage($basCount, $totalReclamations),
            ],
            'categories' => [
                'paiement' => $this->calculatePercentage($problèmesPaiementCount, $totalReclamations),
                'conduite' => $this->calculatePercentage($conduiteInappropriéeCount, $totalReclamations),
                'ponctualite' => $this->calculatePercentage($passagerNonPonctuelCount, $totalReclamations),
                'autres' => $this->calculatePercentage($autresCount, $totalReclamations),
            ],
        ];
    }
}
