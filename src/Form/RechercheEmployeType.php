<?php

namespace App\Form;

use App\Entity\Employe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type as SFType;

class RechercheEmployeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', SFType\TextType::class, array(
                'label'=>'Nom',
                'required'=>true,
                'attr'=>array('size'=>25,'placeholder'=>'Obligatoire')
            ))
            ->add('prenom', SFType\TextType::class, array(
                'label'=>'Prénom',
                'required'=>true,
                'attr'=>array('size'=>25,'placeholder'=>'Obligatoire')
            ))
            ->add('rechercher',  SFType\SubmitType::class)
        ;
    }
}
