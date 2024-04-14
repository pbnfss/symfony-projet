<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as SFType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;

class CreateUserType extends AbstractType
{
        public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('login', SFType\TextType::class, array(
                'label'=>'Login : ',
                'required'=>true,
                'attr'=>array('size'=>50,'placeholder'=>'Obligatoire')
            ))
            ->add('mdp', SFType\TextType::class, array(
                'label'=>'Mot de passe : ',
                'required'=>true,
                'attr'=>array('size'=>50,'placeholder'=>'Obligatoire')
            ))
            ->add('nom', SFType\TextType::class, array(
                'label'=>'Nom : ',
                'required'=>true,
                'attr'=>array('size'=>50,'placeholder'=>'Obligatoire')
            ))
            ->add('prenom', SFType\TextType::class, array(
                'label'=>'Prenom : ',
                'required'=>true,
                'attr'=>array('size'=>50,'placeholder'=>'Obligatoire')
            ))
            ->add('statut', SFType\TextType::class, array(
                'label'=>'Statut : ',
                'required'=>true,
                'attr'=>array('size'=>50,'placeholder'=>'Obligatoire')
            ))
            ->add('Valider utilisateur',  SFType\SubmitType::class)
            ->add('Annuler', ButtonType::class, [
                'attr' => [
                    'onclick' => 'history.go(-1); return false;',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Employe::class,
        ]);
    }
}
