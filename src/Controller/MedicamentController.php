<?php

namespace App\Controller;

use App\Entity\Medicament;
use App\Entity\Commande;
use App\Form\MedicamentType;
use App\Form\CommandeType;
use App\Repository\MedicamentRepository;
use App\Repository\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;



final class MedicamentController extends AbstractController{
    #[Route('/categories', name: 'app_categories', methods: ['GET'])]
public function categories(): Response
{
    // Définition des catégories en dur
    $categories = [
        [
            'slug' => 'general',
            'nom' => 'Médicaments généraux',
            'icone' => 'generalmed.png'
        ],
        [
            'slug' => 'skin-hair',
            'nom' => 'Produits pour le soin de peaux',
            'icone' => 'skincare.png'
        ],
        [
            'slug' => 'child-care',
            'nom' => 'Médicaments pour enfants',
            'icone' => 'child_care.png'
        ],
        [
            'slug' => 'woman-health',
            'nom' => 'Produits pour la santé des femmes',
            'icone' => 'woman_health.png'
        ],
        [
            'slug' => 'dentist',
            'nom' => 'Produits dentaires',
            'icone' => 'dentist.png'
        ],
        [
            'slug' => 'ent',
            'nom' => 'Produits ORL',
            'icone' => 'ent.png'
        ],
        [
            'slug' => 'vitamins',
            'nom' => 'Compléments en vitamines',
            'icone' => 'vitamins.png'
        ],
        
    ];

    return $this->render('categorie/index.html.twig', [
        'categories' => $categories,
    ]);
}


    #[Route('/categorie/{slug}/medicaments', name: 'app_medicament_par_categorie', methods: ['GET'])]
    public function medicamentsParCategorie(string $slug, MedicamentRepository $medicamentRepository): Response
    {
    // Associez les slugs à des descriptions de médicaments
    $descriptions = [
        'general' => 'Médicaments généraux',
        'skin-hair' => 'Produits pour la peau et les cheveux',
        'child-care' => 'Médicaments pour enfants',
        'woman-health' => 'Produits pour la santé des femmes',
        'dentist' => 'Produits dentaires',
        'ent' => 'Produits ORL',
        'homeopathy' => 'Homéopathie',
        'vitamins' => 'Compléments en vitamines'
    ];

    if (!isset($descriptions[$slug])) {
        throw $this->createNotFoundException("Description introuvable");
    }

    // Récupérer les médicaments ayant la même description
    $medicaments = $medicamentRepository->findByDescription($descriptions[$slug]);

    return $this->render('medicament/listbycategory.html.twig', [
        'description' => $descriptions[$slug],
        'medicaments' => $medicaments,
    ]);
}





    #[Route('/medicament/list',name: 'app_medicament_index', methods: ['GET'])]
    public function index(MedicamentRepository $medicamentRepository,Request $request): Response
    {
        $searchTerm = $request->query->get('search', ''); // Récupérer la valeur du champ de recherche

    // Trier et filtrer les médicaments
    $medicaments = $medicamentRepository->createQueryBuilder('m')
        ->where('m.nom_medicament LIKE :search')
        ->setParameter('search', '%' . $searchTerm . '%')
        ->orderBy('m.nom_medicament', 'ASC')
        ->getQuery()
        ->getResult();

    return $this->render('medicament/list_medicaments.html.twig', [
        'medicaments' => $medicaments,
        'searchTerm' => $searchTerm, // Passer la valeur actuelle à la vue
    ]);
    }

    #[Route('/medicament/new', name: 'app_medicament_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $medicament = new Medicament();
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image_medicament = $form->get('image_medicament')->getData();

            if ($image_medicament) {
                // Lire le fichier en tant que BLOB
                $medicament->setImageMedicament(file_get_contents($image_medicament->getPathname()));
            }

            $entityManager->persist($medicament);
            $entityManager->flush();

            return $this->redirectToRoute('app_medicament_index');
        }

        return $this->render('medicament/new.html.twig', [
            'medicament' => $medicament,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/medicament/show', name: 'app_medicament_show', methods: ['GET'])]
    public function show(Medicament $medicament): Response
    {
        return $this->render('medicament/show.html.twig', [
            'medicament' => $medicament,
        ]);
    }

    #[Route('/medicament/{id}/edit', name: 'app_medicament_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Medicament $medicament, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MedicamentType::class, $medicament);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_medicament_index');
        }

        return $this->render('medicament/edit.html.twig', [
            'medicament' => $medicament,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/medicament/{id}/delete', name: 'app_medicament_delete', methods: ['POST'])]
    public function delete(int $id,Request $request, Medicament $medicament, EntityManagerInterface $entityManager, MedicamentRepository $medicamentRepository): Response
    {
        $medicament = $medicamentRepository->find($id);

    if (!$medicament) {
        throw $this->createNotFoundException('Le médicament n\'existe pas.');
    }

    if ($this->isCsrfTokenValid('delete' . $medicament->getId(), $request->request->get('_token'))) {
        // 1️⃣ Détacher les relations avec les commandes avant suppression
        foreach ($medicament->getCommandeMedicament() as $commande) {
            $commande->removeMedicament($medicament);
        }

        $entityManager->flush(); // Mettre à jour les relations

        // 2️⃣ Supprimer le médicament
        $entityManager->remove($medicament);
        $entityManager->flush(); // Valider la suppression
    }

    return $this->redirectToRoute('app_medicament_index');
    }   
}