<?php
// src/Controller/ConsultationController.php

namespace App\Controller;

use App\Entity\Service;
use App\Entity\Consultation;
use App\Form\ConsultationType;
use App\Form\ServiceType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Repository\ConsultationRepository;

final class ConsultationController extends AbstractController
{
    // Route for creating a new consultation
    #[Route('/consultation', name: 'app_consultation', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $consultation = new Consultation();
        
        // Dynamically set the status if needed
        $status = $request->get('status', Consultation::STATUS_IN_PROGRESS); // Default to "En cours de traitement" if not provided
        $consultation->setStatus($status);
        
        // Create the form
        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        // Form submission and validation
        if ($form->isSubmitted() && $form->isValid()) {
            $patientIdentifier = $consultation->getPatientIdentifier();

            // Validate patientIdentifier is not empty
            if (empty($patientIdentifier)) {
                $this->addFlash('error', 'Patient Identifier cannot be empty.');
                return $this->redirectToRoute('app_consultation');
            }

            // If patientIdentifier is set, check for existing consultation
            $existingConsultation = $entityManager->getRepository(Consultation::class)
                ->findOneBy(['patientIdentifier' => $patientIdentifier]);

            if ($existingConsultation) {
                $this->addFlash('error', 'A consultation for this patient already exists.');
                return $this->redirectToRoute('app_consultation');
            }

            // Persist and save the consultation
            $entityManager->persist($consultation);
            $entityManager->flush();

            // Redirect to the confirmation page
            return $this->redirectToRoute('app_consultation_confirmation', [
                'patientIdentifier' => $patientIdentifier ?? 'No Identifier',
            ]);
        }

        // Get all services for the form
        $services = $entityManager->getRepository(Service::class)->findAll();

        return $this->render('consultation/new.html.twig', [
            'form' => $form->createView(),
            'services' => $services,
        ]);
    }

    // Route for consultation confirmation
    #[Route('/consultation/confirmation/{patientIdentifier}', name: 'app_consultation_confirmation')]
    public function consultationConfirmation(string $patientIdentifier): Response
    {
        return $this->render('consultation/confirmation.html.twig', [
            'patientIdentifier' => $patientIdentifier,
        ]);
    }

    // Route for viewing consultations of a patient
    #[Route('/patient/consultations/{patientIdentifier}', name: 'app_patient_consultations')]
    public function patientConsultations(string $patientIdentifier, EntityManagerInterface $entityManager): Response
    {
        $consultations = $entityManager->getRepository(Consultation::class)
            ->findBy(['patientIdentifier' => $patientIdentifier]);

        if (!$consultations) {
            $this->addFlash('info', 'No consultations found for this patient.');
        }

        return $this->render('consultation/view.html.twig', [
            'consultations' => $consultations,
            'patientIdentifier' => $patientIdentifier,
        ]);
    }

    #[Route('/consultation/search', name: 'app_search_consultation')]
    public function searchConsultation(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createFormBuilder()
            ->add('patientIdentifier', TextType::class, [
                'label' => 'Enter Patient Identifier',
            ])
            ->getForm();
    
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $patientIdentifier = $form->getData()['patientIdentifier'];
    
            // If patientIdentifier is empty, do not redirect but show a message
            if (empty($patientIdentifier)) {
                return $this->render('consultation/search.html.twig', [
                    'form' => $form->createView(),
                    'message' => 'Please enter a patient identifier to search.',
                ]);
            }
    
            // Proceed with the normal redirect if identifier is valid (non-empty)
            return $this->redirectToRoute('app_patient_consultations', [
                'patientIdentifier' => $patientIdentifier,
            ]);
        }
    
        return $this->render('consultation/search.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    // Route for editing a consultation (updated version)
    #[Route('/consultation/{id}/edit', name: 'app_consultation_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $entityManager): Response
    {
        $consultation = $entityManager->getRepository(Consultation::class)->find($id);
        if (!$consultation) {
            throw $this->createNotFoundException('Consultation not found.');
        }

        $form = $this->createForm(ConsultationType::class, $consultation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Consultation updated successfully!');
            return $this->redirectToRoute('app_patient_consultations', [
                'patientIdentifier' => $consultation->getPatientIdentifier(),
            ]);
        }

        return $this->render('consultation/edit.html.twig', [
            'form' => $form->createView(),
            'consultation' => $consultation,
        ]);
    }

    // Route for deleting a consultation
    #[Route('/consultation/{id}/delete', name: 'app_delete_consultation')]
    public function delete(int $id, EntityManagerInterface $entityManager): Response
    {
        $consultation = $entityManager->getRepository(Consultation::class)->find($id);

        if (!$consultation) {
            $this->addFlash('error', 'Consultation not found.');
            return $this->redirectToRoute('app_consultation');
        }

        $entityManager->remove($consultation);
        $entityManager->flush();

        $this->addFlash('success', 'Consultation successfully deleted.');
        return $this->redirectToRoute('app_patient_consultations', [
            'patientIdentifier' => $consultation->getPatientIdentifier(),
        ]);
    }

    // Admin route for viewing all consultations
    #[Route('/admin/consultations', name: 'app_admin_consultations')]
    public function adminConsultations(EntityManagerInterface $entityManager): Response
    {
        $consultations = $entityManager->getRepository(Consultation::class)->findAll();

        return $this->render('admin/consultations.html.twig', [
            'consultations' => $consultations,
        ]);
    }

    // Route for editing consultation status (admin only)
    #[Route('/admin/consultation/{id}/edit-status', name: 'app_edit_consultation_status')]
    public function editStatus(Consultation $consultation, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) {
            $status = $request->request->get('status');
            $consultation->setStatus($status); // Update status
            $em->flush(); // Save to the database

            $this->addFlash('success', 'Status updated successfully.');
            return $this->redirectToRoute('app_admin_consultations');
        }

        return $this->render('consultation/edit_status.html.twig', [
            'consultation' => $consultation,
        ]);
    }

    // Route for listing consultations
    #[Route('/consultations', name: 'app_consultations_list')]
    public function listConsultations(ConsultationRepository $consultationRepository)
    {
        // Fetch consultations from the repository
        $consultations = $consultationRepository->findAll();

        return $this->render('admin/consultations_list.html.twig', [
            'consultations' => $consultations,
        ]);
    }
}
