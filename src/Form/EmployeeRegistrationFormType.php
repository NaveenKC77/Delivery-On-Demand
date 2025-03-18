<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmployeeRegistrationFormType extends AbstractRegistrationFormType
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
             ->add('employee', EmployeeFormType::class); // Nested form for employee
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
