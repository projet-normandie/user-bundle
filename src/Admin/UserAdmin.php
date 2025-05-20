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
                'label' => 'user.form.username',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'user.form.email',
                'required' => true,
            ])
            ->add('groups', ModelAutocompleteType::class, [
                'label' => 'user.form.groups',
                'property' => 'name',
                'required' => false,
                'multiple' => true,
                'btn_add' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
                'label' => 'user.form.enabled'
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
                'label' => 'user.form.comment'
            ]);
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('username', null, ['label' => 'user.filter.username'])
            ->add('email', null, ['label' => 'user.filter.email'])
            ->add('enabled', null, ['label' => 'user.filter.enabled'])
            ->add('groups', null, ['label' => 'user.filter.groups']);
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', null, ['label' => 'user.list.id'])
            ->add('username', null, ['label' => 'user.list.username'])
            ->add('email', null, ['label' => 'user.list.email'])
            ->add('groups', null, ['label' => 'user.list.groups'])
            ->add('enabled', null, ['editable' => true, 'label' => 'user.list.enabled'])
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
            ->add('id', null, ['label' => 'user.show.id'])
            ->add('username', null, ['label' => 'user.show.username'])
            ->add('email', null, ['label' => 'user.show.email'])
            ->add('enabled', null, ['label' => 'user.show.enabled'])
            ->add('language', null, ['label' => 'user.show.language'])
            ->add('avatar', null, ['label' => 'user.show.avatar'])
            ->add('nbConnexion', null, ['label' => 'user.show.nb_connexion'])
            ->add('extraData', null, ['label' => 'user.show.extra_data'])
            ->add('comment', null, ['label' => 'user.show.comment'])
            ->end()
            ->with('Connexion', ['class' => 'col-md-6'])
            ->add('lastLogin', null, ['label' => 'user.show.last_login'])
            ->add('password', null, ['label' => 'user.show.password'])
            ->add('confirmationToken', null, ['label' => 'user.show.confirmation_token'])
            ->add('passwordRequestedAt', 'datetime', ['label' => 'user.show.password_requested_at'])
            ->end();
    }
}
