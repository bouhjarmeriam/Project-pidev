<?php

namespace App\Controller;

use App\Repository\DossierMedicaleRepository;
use App\Repository\SejourRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/mon-espace')]
class FrontOfficeController extends AbstractController
{
    #[Route('/', name: 'app_front_office_dashboard')]
    public function dashboard(
        DossierMedicaleRepository $dossierMedicaleRepository,
        SejourRepository $sejourRepository,
        UserRepository $userRepository
    ): Response {
        // TODO: Replace with proper user authentication once user module is integrated
        $user = $userRepository->find(1); // Hardcoded user ID for now

        if (!$user) {
            return $this->render('front_office/dashboard.html.twig', [
                'dossiers' => [],
                'sejours' => [],
                'consultations' => [],
                'notifications' => [],
                'activities' => [],
                'user' => null
            ]);
        }
        
        // Get dossiers for the user
        $dossiers = $dossierMedicaleRepository->findBy(['patient' => $user]);
        
        // Get all sejours from the user's dossiers
        $sejours = [];
        foreach ($dossiers as $dossier) {
            $sejoursDossier = $sejourRepository->findBy(['dossierMedicale' => $dossier]);
            $sejours = array_merge($sejours, $sejoursDossier);
        }
        
        // Get recent activities
        $activities = [];
        foreach ($sejours as $sejour) {
            $activities[] = [
                'icon' => 'hotel',
                'title' => sprintf('Séjour du %s au %s', 
                    $sejour->getDateEntree()->format('d/m/Y'),
                    $sejour->getDateSortie()->format('d/m/Y')
                ),
                'time' => $sejour->getDateEntree()
            ];
        }
        
        // Sort activities by date (most recent first)
        usort($activities, function($a, $b) {
            return $b['time'] <=> $a['time'];
        });
        
        // Limit to 5 most recent activities
        $activities = array_slice($activities, 0, 5);
        
        return $this->render('front_office/dashboard.html.twig', [
            'dossiers' => $dossiers,
            'sejours' => $sejours,
            'consultations' => [],
            'notifications' => [],
            'activities' => $activities,
            'user' => $user
        ]);
    }

    #[Route('/dossier-medical/{id}', name: 'app_front_office_dossier_show')]
    public function showDossier(
        $id,
        DossierMedicaleRepository $dossierMedicaleRepository,
        SejourRepository $sejourRepository,
        UserRepository $userRepository
    ): Response {
        $dossier = $dossierMedicaleRepository->find($id);
        
        if (!$dossier) {
            throw $this->createNotFoundException('Dossier médical not found');
        }
        
        // TODO: Replace with proper user authentication once user module is integrated
        // For now, we'll just check if the dossier exists
        
        $sejours = $sejourRepository->findBy(['dossierMedicale' => $dossier]);
        
        return $this->render('front_office/dossier_show.html.twig', [
            'dossier' => $dossier,
            'sejours' => $sejours,
        ]);
    }

    #[Route('/sejour/{id}', name: 'app_front_office_sejour_show')]
    public function showSejour(
        $id, 
        SejourRepository $sejourRepository,
        UserRepository $userRepository
    ): Response {
        $sejour = $sejourRepository->find($id);
        
        if (!$sejour) {
            throw $this->createNotFoundException('Séjour not found');
        }
        
        // TODO: Replace with proper user authentication once user module is integrated
        // For now, we'll just check if the sejour exists
        
        return $this->render('front_office/sejour_show.html.twig', [
            'sejour' => $sejour,
        ]);
    }
} 