<?php

namespace App\Controller\Admin;

use App\Entity\IllustrationHome;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class IllustrationHomeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return IllustrationHome::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'nom'),
            SlugField::new('slug')->setTargetFieldName('name'),
            ImageField::new('visual', 'image')
                ->setBasePath('uploads/')
                ->setUploadDir('public/uploads')
                ->setUploadedFileNamePattern('[randomhash].[extension]')
                ->setRequired(false)
        ];
    }
    
}
