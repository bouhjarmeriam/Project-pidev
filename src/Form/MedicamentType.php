<?php

namespace App\Form;

use App\Entity\Medicament;
use App\Entity\Commande;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;



class MedicamentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom_medicament')
            ->add('description_medicament')
            ->add('type_medicament')
            ->add('prix_medicament')
            ->add('quantite_stock')
            ->add('date_entree', null, [
                'widget' => 'single_text'
            ])
            ->add('date_expiration', null, [
                'widget' => 'single_text'
            ])
            ->add('image_medicament', FileType::class, [
                'label' => 'Image du Médicament (JPG, PNG)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '2M',
                        'mimeTypes' => ['image/jpg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger une image valide (JPEG, PNG).',
                    ])
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Medicament::class,
        ]);
    }
}
