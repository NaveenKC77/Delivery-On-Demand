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
            ->add('name', TextType::class, ['attr' => ['class' => 'form-control', 'id' => 'name'], 'required' => true, 'label' => 'Product name'])
            ->add('description', TextType::class, ['attr' => ['class' => 'form-control', 'id' => 'description'], 'required' => true, 'label' => 'Product Description'])
            ->add('price', NumberType::class, ['attr' => ['class' => 'form-control', 'id' => 'price'], 'required' => true, 'label' => 'Product price'])
            ->add('available')
            ->add('imagePath', FileType::class, ['label' => 'Image', 'required' => false])
             ->add('category', EntityType::class, ['attr' => ['class' => 'form-control', 'id' => 'category', 'required' => true],
                 'class' => Category::class,
                 'choice_label' => 'name',
                 'required' => true,
             ]);

        $builder->get('imagePath')
             ->addModelTransformer(new CallbackTransformer(
                 function ($imagePath) {
                     return null;
                 },
                 function ($imagePath) {
                     return $imagePath;
                 }
             ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
