<?php

namespace App\Form;

use App\Entity\Announce;
use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnounceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de l\'annonce',
                'constraints' =>[
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre titre doit contenir au moins deux caractères alphabétiques',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ])
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description'
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Prix'
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville'
            ])
            ->add('zipcode', IntegerType::class, [
                'label' => 'Code Postal'
            ])
            ->add('category', EntityType::class, [
                // looks for choices from this entity
                'label' => 'Catégorie',
                'class' => Category::class,
            
                // uses the User.username property as the visible option string
                'choice_label' => 'name',
                //'mapped' => false
            
                // used to render a select box, check boxes or radios
                'multiple' => true,
                // 'expanded' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => $options['edit_mode'] ? 'Modifier annonce' : 'Ajouter annonce'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Announce::class,
            'edit_mode' => false,
        ]);
    }
}
