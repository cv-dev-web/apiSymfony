<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Entity\Grade;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class UserCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    // public function configureActions(Actions $actions): Actions
    // {
    //     return $actions;
    // }

    
    public function configureFields(string $pageName): iterable
    {
         return [
            IdField::new('id')->hideOnForm(),
            TextField::new('lastName','Nom'),
            TextField::new('firstName','Prenom'),
            DateTimeField::new('birthDate','Date de naissance'),
            EmailField::new('email'),
            TextField::new('password','Mot de passe'),
            TextField::new('avatar'),
            BooleanField::new('isActif')->hideOnForm(),
            BooleanField::new('firstConnexion')->hideOnForm(),
            AssociationField::new('grade','Rôle'),
            TextField::new('phone','Téléphone'),
            DateTimeField::new('userCreationDate')->hideOnForm(),
         ];
    }

    public function persistEntity(EntityManagerInterface $em,$entityInstance): void
    {
        if(!$entityInstance instanceof User) return;
        $entityInstance
            ->setUserCreationDate(new \DateTimeImmutable)
            ->setIsActif(false)
            ->setFirstConnexion(true);

        parent :: persistEntity($em,$entityInstance);
    }
    
}
