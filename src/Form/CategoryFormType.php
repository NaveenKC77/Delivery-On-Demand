<?php

namespace App\Form;

use App\Entity\Category;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                ['attr' => ['class' => 'form-control', 'id' => 'name'], 'required' => true,
                    'label' => 'Category name']
            )
            ->add(
                'description',
                TextType::class,
                ['attr' => ['class' => 'form-control',
                    'id' => 'description'],
                    'required' => true,
                    'label' => 'Category Description']
            )
            ->add('active')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
