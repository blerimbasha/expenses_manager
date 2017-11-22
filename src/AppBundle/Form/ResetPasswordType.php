<?php

namespace AppBundle\Form;


use AppBundle\Entity\ResetPassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('newPassword', RepeatedType::class, [
            'type' => PasswordType::class,
            'invalid_message' => 'password.fields_must_match.',
            'required' => true,
            'first_options'  => ['label' => 'user.password'],
            'second_options' => ['label' => 'user.repeat_password'],
        ])
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ResetPassword::class
        ]);
    }
}
