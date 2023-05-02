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
            ->add('status', null, ['label' => 'label.status'])
            ->add('groups', ModelAutocompleteType::class, [
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
            ->add('status', null, ['label' => 'label.status'])
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
            ->add('status', null, ['label' => 'label.status'])
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
            ->add('lastLogin', null, ['label' => 'label.lastLogin'])
            ->add('status', null, ['label' => 'label.status'])
            ->add('locale', null, ['label' => 'label.locale'])
            ->add('avatar', null, ['label' => 'label.avatar'])
            ->add('nbConnexion', null, ['label' => 'label.nbConnexion'])
            ->add('nbForumMessage', null, ['label' => 'label.nbForumMessage'])
            ->add('comment', null, ['label' => 'label.comment'])
            ->end()
            ->with('Connexion', ['class' => 'col-md-6'])
            ->add('password', null, ['label' => 'label.password'])
            ->add('confirmation_token', null, ['label' => 'label.confirmation_token'])
            ->add('password_requested_at', 'datetime', ['label' => 'label.password_requested_at'])
            ->end()
            ->with('IP')
            ->add('userIp', null, ['label' => 'label.userIp']);
    }
}
