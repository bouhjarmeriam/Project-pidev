<?php
namespace App\Controller;

use App\Entity\Salle;
use App\Form\SalleType;
use App\Repository\SalleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/salle')]
class SalleController extends AbstractController
{
    #[Route('/', name: 'salle_index', methods: ['GET'])]
    public function index(SalleRepository $salleRepository): Response
    {
        return $this->render('salle/index.html.twig', [
            'salles' => $salleRepository->findAll(),
        ]);
    }
    #[Route('/add', name: 'salle_add', methods: ['GET', 'POST'])]
    public function newDepartement(Request $request, EntityManagerInterface $entityManager): Response
    {
        $salle = new Salle();
        $form = $this->createForm(SalleType::class, $salle);
        
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
    
            if ($image) {
                $imageName = md5(uniqid()) . '.' . $image->guessExtension();
                $image->move($this->getParameter('upload_directory'), $imageName);
                $salle->setImage('uploads/'.$imageName);
            }
    
            // Enregistrer le département uniquement si le formulaire est valide
            $entityManager->persist($salle);
            $entityManager->flush();
    
            // Ajouter un message flash de succès
            $this->addFlash('success', 'Le département a été ajouté avec succès !');
    
            // Rediriger vers la liste des départements après l'ajout
            return $this->redirectToRoute('salle_index');
        }
    
        return $this->render('salle/add.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/edit', name: 'salle_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Salle $salle, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SalleType::class, $salle);
        $form->handleRequest($request);
        $oldImage = $salle->getImage();

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();

            if ($image) {
                $imageName = md5(uniqid()) . '.' . $image->guessExtension();
                $uploadPath = $this->getParameter('upload_directory');
                $image->move($uploadPath, $imageName);
                $salle->setImage('uploads/' . $imageName);

                if ($oldImage && file_exists($oldImage)) {
                    unlink($oldImage);
                }
            } else {
                $salle->setImage($oldImage);
            }

            $entityManager->persist($salle);
            $entityManager->flush();

            $this->addFlash('success', 'La salle a été mise à jour avec succès !');

            return $this->redirectToRoute('salle_index');
        }

        return $this->render('salle/edit.html.twig', [
            'salle' => $salle,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/delete/{id}', name: 'salle_delete', methods: ['POST'])]
    public function delete(Request $request, Salle $salle, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $salle->getId(), $request->request->get('_token'))) {
            $entityManager->remove($salle);
            $entityManager->flush();
            $this->addFlash('success', 'Salle supprimée avec succès !');
        }

        return $this->redirectToRoute('salle_index');
    }
}

