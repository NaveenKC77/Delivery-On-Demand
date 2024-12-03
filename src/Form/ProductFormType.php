<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextType::class)
            ->add('price', NumberType::class)
            ->add('stock', NumberType::class, ['required' => false])
            ->add('imagePath', FileType::class, ['required' => false])
            // ->add('createdAt', null, [
            //     'widget' => 'single_text', 'required'=>false
            // ])
            // ->add('updatedAt', null, [
            //     'widget' => 'single_text', 'required' =>false
            // ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
            ])
        ;

        $builder->get('imagePath')
            ->addModelTransformer(
                new CallbackTransformer(
                    function ($imagePath) {
                        return null;
                    },
                    function ($imagePath) {
                        return $imagePath;
                    }
                )
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
