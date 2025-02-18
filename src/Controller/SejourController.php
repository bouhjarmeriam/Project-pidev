<?php

namespace App\Controller;

use App\Entity\Sejour;
use App\Form\SejourType;
use App\Repository\SejourRepository;
use App\Repository\DossierMedicaleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sejour')]
class SejourController extends AbstractController
{
    #[Route('/', name: 'app_sejour_index', methods: ['GET'])]
    public function index(SejourRepository $sejourRepository): Response
    {
        return $this->render('sejour/index.html.twig', [
            'sejours' => $sejourRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_sejour_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request, 
        EntityManagerInterface $entityManager,
        DossierMedicaleRepository $dossierMedicaleRepository
    ): Response
    {
        $sejour = new Sejour();
        $form = $this->createForm(SejourType::class, $sejour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sejour);
            $entityManager->flush();

            $this->addFlash('success', 'Le séjour a été créé avec succès.');
            return $this->redirectToRoute('app_sejour_index');
        }

        return $this->render('sejour/new.html.twig', [
            'sejour' => $sejour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sejour_show', methods: ['GET'])]
    public function show(Sejour $sejour): Response
    {
        return $this->render('sejour/show.html.twig', [
            'sejour' => $sejour,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_sejour_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sejour $sejour, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SejourType::class, $sejour);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le séjour a été modifié avec succès.');
            return $this->redirectToRoute('app_sejour_index');
        }

        return $this->render('sejour/edit.html.twig', [
            'sejour' => $sejour,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_sejour_delete', methods: ['POST'])]
    public function delete(Request $request, Sejour $sejour, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sejour->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sejour);
            $entityManager->flush();
            $this->addFlash('success', 'Le séjour a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_sejour_index');
    }
} 