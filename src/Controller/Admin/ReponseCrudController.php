<?php

namespace App\Controller\Admin;

use App\Entity\Reponse;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;

class ReponseCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reponse::class;
    }

    public function configureFields(string $pageName): iterable
    {
        // Définir les champs de base
        $fields = [
            IdField::new('id', 'ID')->hideOnForm(),
            DateTimeField::new('dateReponse', 'Date de Réponse')->setFormat('short'),
            BooleanField::new('lue', 'Lue'),
            AssociationField::new('reclamation', 'Réclamation')->setFormTypeOptions(['choice_label' => 'titre']),
            TextEditorField::new('texte', 'Réponse de l\'administrateur')->onlyOnIndex(),  // Affiche dans la liste
        ];

        // Si on est sur la page "Détail", ajouter le champ des détails
        if (Crud::PAGE_DETAIL === $pageName) {
            return array_merge($fields, [
                TextEditorField::new('texte', 'Réponse complète de l\'administrateur')->onlyOnDetail(),
                TextEditorField::new('details', 'Réponse de l\'utilisateur')->onlyOnDetail(),  // Affiche la réponse de l'utilisateur dans les détails
            ]);
        }

        return $fields;
    }

    public function configureActions(Actions $actions): Actions
    {
        // Personnalisation des actions
        $actions = $actions
            ->update(Crud::PAGE_EDIT, Action::SAVE_AND_RETURN, function (Action $action) {
                return $action
                    ->setLabel('Sauvegarder et Retourner')
                    ->setIcon('fa fa-check');
            });

        return $actions;
    }
}
