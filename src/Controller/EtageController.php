<?php

namespace App\Controller;

use App\Entity\Etage;
use App\Form\EtageType;
use App\Repository\EtageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/etage')]
class EtageController extends AbstractController
{
    #[Route('/', name: 'etage_index', methods: ['GET'])]
    public function index(EtageRepository $etageRepository): Response
    {
        return $this->render('etage/index.html.twig', [
            'etages' => $etageRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'etage_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $etage = new Etage();
        $form = $this->createForm(EtageType::class, $etage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($etage);
            $entityManager->flush();

            return $this->redirectToRoute('etage_index');
        }

        return $this->render('etage/new.html.twig', [
            'etage' => $etage,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'etage_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Etage $etage, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EtageType::class, $etage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('etage_index');
        }

        return $this->render('etage/edit.html.twig', [
            'etage' => $etage,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'etage_delete', methods: ['POST'])]
    public function delete(Request $request, Etage $etage, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$etage->getId(), $request->request->get('_token'))) {
            $entityManager->remove($etage);
            $entityManager->flush();
        }

        return $this->redirectToRoute('etage_index');
    }
}
