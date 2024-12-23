<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerRegistrationFormType extends AbstractRegistrationFormType
{
    /**
     * This will suppress all the PMD warnings in
     * this class.
     *
     * @SuppressWarnings(PHPMD)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::returnBuildForm($builder, $options)
            ->add('customer', CustomerFormType::class); // Nested form for customer
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
