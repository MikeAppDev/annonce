<?php

namespace App\Controller\Admin;

use App\Entity\Announce;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;

class AnnounceCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Announce::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('title');
        yield AssociationField::new('category');
        yield AssociationField::new('picture');
        yield TextEditorField::new('description');
    }
    
}
