<?php

namespace App\Form;

use App\Entity\Sejour;
use App\Entity\DossierMedicale;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\GreaterThan;
use Symfony\Component\Validator\Constraints\PositiveOrZero;
use Doctrine\ORM\EntityRepository;

class SejourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateEntree', DateTimeType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date d\'entrée est requise']),
                ],
            ])
            ->add('dateSortie', DateTimeType::class, [
                'widget' => 'single_text',
                'constraints' => [
                    new NotBlank(['message' => 'La date de sortie est requise']),
                    new GreaterThan([
                        'propertyPath' => 'parent.all[dateEntree].data',
                        'message' => 'La date de sortie doit être postérieure à la date d\'entrée',
                    ]),
                ],
            ])
            ->add('typeSejour', ChoiceType::class, [
                'choices' => [
                    'Hospitalisation' => 'Hospitalisation',
                    'Consultation' => 'Consultation',
                    'Urgence' => 'Urgence',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le type de séjour est requis']),
                ],
            ])
            ->add('fraisSejour', NumberType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Les frais de séjour sont requis']),
                    new PositiveOrZero(['message' => 'Les frais doivent être positifs ou nuls']),
                ],
            ])
            ->add('moyenPaiement', ChoiceType::class, [
                'choices' => [
                    'Carte bancaire' => 'Carte bancaire',
                    'Espèces' => 'Espèces',
                    'Chèque' => 'Chèque',
                    'Assurance' => 'Assurance',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le moyen de paiement est requis']),
                ],
            ])
            ->add('statutPaiement', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'En attente',
                    'Payé' => 'Payé',
                    'Annulé' => 'Annulé',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le statut de paiement est requis']),
                ],
            ])
            ->add('prixExtras', NumberType::class, [
                'required' => false,
                'constraints' => [
                    new PositiveOrZero(['message' => 'Le prix des extras doit être positif ou nul']),
                ],
            ])
            ->add('dossierMedicale', EntityType::class, [
                'class' => DossierMedicale::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.dateDeCreation', 'DESC');
                },
                'choice_label' => function (DossierMedicale $dossier) {
                    return sprintf(
                        '#%d - %s %s',
                        $dossier->getId(),
                        $dossier->getPatient()->getNomUser(),
                        $dossier->getPatient()->getPrenomUser()
                    );
                },
                'constraints' => [
                    new NotBlank(['message' => 'Le dossier médical est requis']),
                ],
                'attr' => [
                    'class' => 'select-custom',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sejour::class,
        ]);
    }
} 