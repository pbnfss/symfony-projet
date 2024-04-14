<?php

namespace App\Form;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type as SFType;
use App\Entity\Formation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use App\Entity\Produit;
use App\Entity\Employe;
use Doctrine\Persistence\ManagerRegistry;



class CreateFormationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateDebut', SFType\DateType::class, array(
                'label'=>'Date de début : ',
                'required'=>true,
                'format'=>'dd-MM-yyyy',
            ))
            ->add('nbHeures', SFType\TextType::class, array(
                'label'=>'Nombre heures : ',
                'required'=>true,
                'attr'=>array('size'=>25,'placeholder'=>'Obligatoire')
            ))
            ->add('departement', SFType\TextType::class, array(
                'label'=>'Département : ',
                'required'=>true,
                'attr'=>array('size'=>50,'placeholder'=>'Obligatoire')
            ))
            ->add('produit', EntityType::class, [
                'class' => 'App\Entity\Produit',
                'choice_label' => 'libelle',
                'label' => 'Sélectionner un produit',
            ])
            ->add('Ajouter',  SFType\SubmitType::class)
            ->add('Annuler', ButtonType::class, [
                'attr' => [
                    'onclick' => 'history.go(-1); return false;',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Formation::class,
        ]);
    }
}
