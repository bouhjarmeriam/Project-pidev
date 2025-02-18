<?php

namespace App\Form;

use App\Entity\DossierMedicale;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Doctrine\ORM\EntityRepository;

class DossierMedicaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDeCreation', DateTimeType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date de création est requise']),
                ],
            ])
            ->add('historiqueDesMaladies', TextareaType::class, [
                'required' => false,
            ])
            ->add('operationsPassees', TextareaType::class, [
                'required' => false,
            ])
            ->add('consultationsPassees', TextareaType::class, [
                'required' => false,
            ])
            ->add('statutDossier', ChoiceType::class, [
                'choices' => [
                    'Actif' => 'Actif',
                    'En attente' => 'En attente',
                    'Archivé' => 'Archivé',
                    'Fermé' => 'Fermé',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le statut du dossier est requis']),
                ],
            ])
            ->add('notes', TextareaType::class, [
                'required' => false,
            ])
            ->add('imageFile', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG, GIF)',
                    ])
                ],
            ])
            ->add('patient', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%ROLE_PATIENT%')
                        ->orderBy('u.nomUser', 'ASC')
                        ->addOrderBy('u.prenomUser', 'ASC');
                },
                'choice_label' => function (User $user) {
                    return sprintf('%s %s', $user->getNomUser(), $user->getPrenomUser());
                },
                'placeholder' => 'Sélectionner un patient',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Le patient est requis']),
                ],
                'attr' => [
                    'class' => 'select-custom',
                ]
            ])
            ->add('medecin', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role', '%ROLE_MEDECIN%')
                        ->orderBy('u.nomUser', 'ASC')
                        ->addOrderBy('u.prenomUser', 'ASC');
                },
                'choice_label' => function (User $user) {
                    return sprintf('%s %s', $user->getNomUser(), $user->getPrenomUser());
                },
                'placeholder' => 'Sélectionner un médecin',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Le médecin est requis']),
                ],
                'attr' => [
                    'class' => 'select-custom',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DossierMedicale::class,
        ]);
    }
} 