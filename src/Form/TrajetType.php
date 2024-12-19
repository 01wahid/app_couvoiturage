<?php


namespace App\Form;

use App\Entity\Trajet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TrajetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nomConducteur', TextType::class, [
                'label' => 'Nom du conducteur',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('depart', TextType::class, [
                'label' => 'Lieu de départ',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('destination', TextType::class, [
                'label' => 'Destination',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('dateHeure', DateTimeType::class, [
                'label' => 'Date et heure',
                'widget' => 'single_text',  
                'attr' => ['class' => 'form-control'],
            ])
            ->add('nombrePlaces', NumberType::class, [
                'label' => 'Nombre de places',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('contributionFrais', NumberType::class, [
                'label' => 'Contribution aux frais',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Trajet::class,
        ]);
    }
}
