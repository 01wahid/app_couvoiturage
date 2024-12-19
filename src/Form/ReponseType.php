<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\Reponse;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('texte', TextareaType::class, [
                'label' => 'Réponse de l\'administrateur',
                'attr' => ['class' => 'form-control', 'rows' => 5, 'placeholder' => 'Entrez votre réponse...']
            ])
            ->add('details', TextareaType::class, [
                'label' => 'Détails supplémentaires (si nécessaire)',
                'required' => false,
                'attr' => ['class' => 'form-control', 'rows' => 5, 'placeholder' => 'Ajoutez des détails supplémentaires ici...']
            ])
//            ->add('submit', SubmitType::class, [
//                'label' => 'Envoyer la réponse',
//                'attr' => ['class' => 'btn btn-primary']
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reponse::class,
        ]);
    }
}