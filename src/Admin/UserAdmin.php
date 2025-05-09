<?php

namespace ProjetNormandie\UserBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class UserAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'pnu_admin_user';

    /**
     * @param RouteCollectionInterface $collection
     */
    protected function configureRoutes(RouteCollectionInterface $collection): void
    {
        $collection
            ->remove('create')
            ->remove('export')
            ->remove('delete');
    }

    /**
     * @param FormMapper $form
     */
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('username', TextType::class, [
                'label' => 'label.username',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'label.email',
                'required' => true,
            ])
            ->add('groups', ModelAutocompleteType::class, [
                'label' => 'label.groups',
                'property' => 'name',
                'required' => false,
                'multiple' => true,
                'btn_add' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
                'label' => 'label.enabled'
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
                'label' => 'label.comment'
            ]);
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('username', null, ['label' => 'label.username'])
            ->add('email', null, ['label' => 'label.email'])
            ->add('enabled', null, ['label' => 'label.enabled'])
            ->add('groups', null, ['label' => 'label.groups']);
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', null, ['label' => 'label.id'])
            ->add('username', null, ['label' => 'label.username'])
            ->add('email', null, ['label' => 'label.email'])
            ->add('groups', null, ['label' => 'label.groups'])
            ->add('enabled', null, ['editable' => true, 'label' => 'label.enabled'])
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ]
            ]);
    }

    /**
     * @param ShowMapper $show
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->with('User', ['class' => 'col-md-6'])
            ->add('id', null, ['label' => 'label.id'])
            ->add('username', null, ['label' => 'label.username'])
            ->add('email', null, ['label' => 'label.email'])
            ->add('enabled', null, ['label' => 'label.enabled'])
            ->add('locale', null, ['label' => 'label.locale'])
            ->add('avatar', null, ['label' => 'label.avatar'])
            ->add('nbConnexion', null, ['label' => 'label.nbConnexion'])
            ->add('extraData', null, ['label' => 'label.extraData'])
            ->add('comment', null, ['label' => 'label.comment'])
            ->end()
            ->with('Connexion', ['class' => 'col-md-6'])
            ->add('lastLogin', null, ['label' => 'label.lastLogin'])
            ->add('password', null, ['label' => 'label.password'])
            ->add('confirmationToken', null, ['label' => 'label.confirmationToken'])
            ->add('passwordRequestedAt', 'datetime', ['label' => 'label.passwordRequestedAt'])
            ->end();
    }
}
