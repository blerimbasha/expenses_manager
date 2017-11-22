<?php
namespace AppBundle\Form;

use AppBundle\Entity\ChangePassword;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('oldPassword', 'password');
        $builder->add('newPassword', 'repeated', array(
            'type' => 'password',
            'invalid_message' => 'password.fields_must_match.',
            'required' => true,
            'first_options'  => array('label' => 'password'),
            'second_options' => array('label' => 'password.repeat_password'),
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => ChangePassword::class
        ]);
    }

}
