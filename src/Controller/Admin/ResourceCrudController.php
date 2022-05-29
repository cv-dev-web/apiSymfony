<?php

namespace App\Controller\Admin;

use DateTimeImmutable;
use App\Entity\Resource;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ResourceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Resource::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('title', 'Titre de la ressource'),
            BooleanField::new('visibility')->hideOnForm(),
            DateTimeField::new('creationDate')->hideOnForm(),
            AssociationField::new('user','Utilisateur'),
            AssociationField::new('category','Catégorie'),
            AssociationField::new('contents','Contenu'),
            AssociationField::new('type','Type'),
            BooleanField::new('modoValid')->hideOnForm(),
            AssociationField::new('levels','Levels'),
            AssociationField::new('status','Statut'),
            AssociationField::new('comments','Commentaires'),
            TextEditorField::new('text','Description'),
        ];
    }

    public function persistEntity(EntityManagerInterface $em,$entityInstance): void
    {
        if(!$entityInstance instanceof Resource) return;
        $entityInstance
            ->setCreationDate(new \DateTimeImmutable)
            ->setVisibility(false)
            ->setModoValid(false);

        parent :: persistEntity($em,$entityInstance);
    }
    
}
