<?php

namespace App\Controller;

use App\Entity\Departement;
use App\Form\DepartementType;
use App\Repository\DepartementRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;

#[Route('/departement')] // Préfixe des routes
class DepartementController extends AbstractController
{
    #[Route('/', name: 'departement_index', methods: ['GET'])]
    public function index(DepartementRepository $departementRepository): Response
    {
        return $this->render('departement/index.html.twig', [
            'departements' => $departementRepository->findAll(),
        ]);
    }

    #[Route('/add', name: 'departement_add', methods: ['GET', 'POST'])]
    public function newDepartement(Request $request, EntityManagerInterface $entityManager): Response
    {
        $departement = new Departement();
        $form = $this->createForm(DepartementType::class, $departement);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
    
            if ($image) {
                $imageName = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('upload_directory'), $imageName);
                $departement->setImage('uploads/'.$imageName);
            }
    
            // Enregistrer le département uniquement si le formulaire est valide
            $entityManager->persist($departement);
            $entityManager->flush();
    
            // Ajouter un message flash de succès
            $this->addFlash('success', 'Le département a été ajouté avec succès !');
    
            // Rediriger vers la liste des départements après l'ajout
            return $this->redirectToRoute('departement_index');
        }
    
        return $this->render('departement/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/{id}/edit', name: 'departement_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Departement $departement, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DepartementType::class, $departement);
        $form->handleRequest($request);
        $oldImage = $departement->getImage();
    
        if ($form->isSubmitted() && $form->isValid()) {
            // Mise à jour de l'image si une nouvelle est téléchargée
            $image = $form->get('image')->getData();
            if ($image) {
                $imageName = md5(uniqid()) . '.' . $image->guessExtension();
                $uploadPath = $this->getParameter('upload_directory');
                $image->move($uploadPath, $imageName);
                $departement->setImage('uploads/'.$imageName);
    
                // Supprimer l'ancienne image si elle existe
                if ($oldImage && file_exists($oldImage)) {
                    unlink($oldImage);
                }
            } else {
                // Si aucune nouvelle image n'est téléchargée, conserver l'ancienne
                $departement->setImage($oldImage);
            }
    
            // Enregistrement des modifications
            $entityManager->persist($departement);
            $entityManager->flush();
    
            // Ajout d'un message flash de succès
            $this->addFlash('success', 'Le département a été mis à jour avec succès !');
    
            // Redirection après l'édition
            return $this->redirectToRoute('departement_index');
        }
    
        return $this->render('departement/edit.html.twig', [
            'departement' => $departement,
            'form' => $form->createView(),
        ]);
    }
    

    #[Route('/departement/{id}', name: 'departement_delete', methods: ['POST'])]
    public function delete(Request $request, Departement $departement, EntityManagerInterface $entityManager): Response
    {
        // Vérification du token CSRF
        if ($this->isCsrfTokenValid('delete'.$departement->getId(), $request->request->get('_token'))) {
            // Suppression du département
            $entityManager->remove($departement);
            $entityManager->flush();
        }

        // Redirection après la suppression
        return $this->redirectToRoute('departement_index');
    }
}
