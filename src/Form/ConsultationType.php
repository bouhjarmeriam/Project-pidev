<?php
// src/Form/ConsultationType.php

namespace App\Form;

use App\Entity\Consultation;
use App\Entity\Service;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class ConsultationType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('service', EntityType::class, [
                'class' => Service::class,
                'choice_label' => 'name',
                'label' => 'Choisir un Service',
            ])
            ->add('date', null, [
                'constraints' => [
                    new NotBlank(['message' => 'Date est obligatoire.'])
                ],
            ])
            ->add('patientIdentifier', TextType::class, [
                'required' => false, // Disable HTML5 required validation
                'constraints' => [
                    new NotBlank([
                        'message' => 'Patient identifier est obligatoire.'
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => 'Patient identifier doit avoir {{ limit }} characters long.',
                    ]),
                    new Regex([
                        'pattern' => '/^[a-zA-Z0-9]*$/',  // Example pattern, adjust it to your needs
                        'message' => 'Patient identifier doit avoir alphanumeric.'
                    ])
                ],
                'label' => 'Patient Identifier',
                'attr' => ['placeholder' => 'Entrez votre  patient identifier'],
                'empty_data' => '',
            ])
        ;

        // Only show the 'status' field to admins
        if ($this->security->isGranted('ROLE_ADMIN')) {
            $builder->add('status', ChoiceType::class, [
                'choices' => [
                    'En cours de traitement' => Consultation::STATUS_IN_PROGRESS,
                    'Confirmed' => Consultation::STATUS_CONFIRMED,
                    'Done' => Consultation::STATUS_DONE,
                ],
                'label' => 'Status',
            ]);
        } else {
            // Default status value for non-admin users
            $builder->add('status', HiddenType::class, [
                'data' => Consultation::STATUS_IN_PROGRESS, // Hide status for non-admins
                'mapped' => false, // This field won't be mapped to the entity
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Consultation::class,
        ]);
    }
}
