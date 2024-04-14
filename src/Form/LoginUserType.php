<?php

namespace App\Form;

use Symfony\Component\Form\Extension\Core\Type as SFType;
use App\Entity\Employe;
use Doctrine\Persistence\ManagerRegistry;
use http\Env\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LoginUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', SFType\TextType::class, array(
                'label'=>'Login',
                'required'=>true,
                'attr'=>array('size'=>50,'placeholder'=>'Obligatoire')
            ))
            ->add('mdp', SFType\PasswordType::class, array(
                'label'=>'Mot de passe',
                'required'=>true,
                'attr'=>array('size'=>50,'placeholder'=>'Obligatoire')
            ))
            ->add('Connexion',  SFType\SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
