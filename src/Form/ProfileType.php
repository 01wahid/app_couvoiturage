<?php

    namespace App\Form;

    use App\Entity\User;
    use Symfony\Component\Form\AbstractType;
    use Symfony\Component\Form\Extension\Core\Type\EmailType;
    use Symfony\Component\Form\Extension\Core\Type\TextType;
    use Symfony\Component\Form\Extension\Core\Type\TelType;
    use Symfony\Component\Form\FormBuilderInterface;
    use Symfony\Component\OptionsResolver\OptionsResolver;

    class ProfileType extends AbstractType
    {
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
    $builder
    ->add('email', EmailType::class, [
    'label' => 'Email',
    'attr' => ['class' => 'form-control'],
    ])
    ->add('telephone', TelType::class, [
    'label' => 'Téléphone',
    'attr' => ['class' => 'form-control'],
    ])
    ->add('nom', TextType::class, [
    'label' => 'Nom',
    'attr' => ['class' => 'form-control'],
    ])
    ->add('prenom', TextType::class, [
    'label' => 'Prénom',
    'attr' => ['class' => 'form-control'],
    ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
    $resolver->setDefaults([
    'data_class' => User::class,
    ]);
    }
}
