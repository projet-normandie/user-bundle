<?php
namespace ProjetNormandie\UserBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as SonataUserAdmin;
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

class UserAdmin extends SonataUserAdmin
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
                'label' => 'Username',
                'required' => true,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
            ])
            ->add('status')
            ->add('groups', ModelAutocompleteType::class, [
                'property' => 'name',
                'required' => false,
                'multiple' => true,
                'btn_add' => false,
            ])
            ->add('enabled', CheckboxType::class, [
                'required' => false,
            ])
            ->add('comment', TextareaType::class, [
                'required' => false,
            ]);
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('username')
            ->add('email')
            ->add('status')
            ->add('enabled')
            ->add('groups');
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id')
            ->add('username')
            ->add('email')
            ->add('status')
            ->add('enabled', null, ['editable' => true])
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
            ->add('id')
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('lastLogin')
            ->add('status')
            ->add('locale')
            ->add('avatar')
            ->add('nbConnexion')
            ->add('nbForumMessage')
            ->add('comment')
            ->end()
            ->with('Connexion', ['class' => 'col-md-6'])
            ->add('locked', 'boolean')
            ->add('salt')
            ->add('password')
            ->add('expired', 'boolean')
            ->add('expires_at', 'datetime')
            ->add('confirmation_token')
            ->add('password_requested_at', 'datetime')
            ->add('credentials_expired', 'boolean')
            ->add('credentials_expired_at', 'datetime')
            ->end()
            ->with('IP')
            ->add('userIp');
    }
}
