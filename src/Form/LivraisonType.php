<?php

namespace App\Form;

use App\Entity\Livraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LivraisonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('expediteur', TextType::class, [
                'label' => "Expéditeur",
                'attr' => ['placeholder' => "Nom de l'expéditeur"]
            ])
            ->add('destinataire', TextType::class, [
                'label' => "Destinataire",
                'attr' => ['placeholder' => "Nom du destinataire"]
            ])
            ->add('adresseDepart', TextType::class, [
                'label' => "Adresse de départ",
                'attr' => ['placeholder' => "Adresse de départ"]
            ])
            ->add('adresseArrivee', TextType::class, [
                'label' => "Adresse d'arrivée",
                'attr' => ['placeholder' => "Adresse d'arrivée"]
            ])
            ->add('dateLivraison', DateTimeType::class, [
                'label' => "Date de livraison",
                'widget' => 'single_text'
            ])
            ->add('etat', ChoiceType::class, [
                'label' => "État de la livraison",
                'choices' => [
                    'En attente' => 'En attente',
                    'En cours' => 'En cours',
                    'Livrée' => 'Livrée'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Livraison::class,
        ]);
    }
}