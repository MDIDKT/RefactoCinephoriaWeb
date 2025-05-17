<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct (UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public static function getEntityFqcn (): string
    {
        return User::class;
    }

    public function configureActions (Actions $actions): Actions
    {
        return $actions
            ->add (Crud::PAGE_EDIT, Action::INDEX)
            ->add (Crud::PAGE_INDEX, Action::DETAIL)
            ->add (Crud::PAGE_EDIT, Action::DETAIL);
    }

    public function configureFields (string $pageName): iterable
    {


        $fields = [
            IdField::new ('id')->hideOnForm (),
            EmailField::new ('email'),
        ];

        $password = TextField::new ('password')
            ->setFormType (PasswordType::class)
            ->setRequired ($pageName === Crud::PAGE_NEW)
            ->onlyOnForms ();
        $fields[] = $password;

        $roles = ChoiceField::new ('roles')
            ->setChoices (['User' => 'ROLE_USER', 'Admin' => 'ROLE_ADMIN', 'Employee' => 'ROLE_EMPLOYEE'])
            ->allowMultipleChoices ()
            ->autocomplete ()
            ->setRequired (true);
        $fields[] = $roles;

        return $fields;
    }

    public function createNewFormBuilder (EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createNewFormBuilder ($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener ($formBuilder);
    }

    public function createEditFormBuilder (EntityDto $entityDto, KeyValueStore $formOptions, AdminContext $context): FormBuilderInterface
    {
        $formBuilder = parent::createEditFormBuilder ($entityDto, $formOptions, $context);
        return $this->addPasswordEventListener ($formBuilder);
    }

    private function addPasswordEventListener (FormBuilderInterface $formBuilder): FormBuilderInterface
    {
        return $formBuilder->addEventListener (FormEvents::POST_SUBMIT, $this->hashPassword ());
    }

    private function hashPassword ()
    {
        return function($event) {
            $form = $event->getForm ();
            if (!$form->isValid ()) {
                return;
            }

            // Récupérer l'entité User en cours d'édition
            $user = $form->getData ();

            // Vérifie que l'entité est bien de type User
            if (!$user instanceof User) {
                return;
            }

            $password = $form->get ('password')->getData ();
            if ($password === null) {
                return;
            }

            // Hasher le mot de passe en utilisant l'utilisateur courant
            $hash = $this->userPasswordHasher->hashPassword ($user, $password);
            $user->setPassword ($hash);
        };
    }
}
