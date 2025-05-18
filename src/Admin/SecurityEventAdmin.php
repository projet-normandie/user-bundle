<?php

namespace ProjetNormandie\UserBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Route\RouteCollectionInterface;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\DoctrineORMAdminBundle\Filter\DateTimeFilter;
use Sonata\DoctrineORMAdminBundle\Filter\StringFilter;
use Sonata\Form\Type\DateTimePickerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;

/**
 * Admin class for the SecurityEvent entity
 */
class SecurityEventAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'pnu_admin_security_event';
    protected $baseRoutePattern = 'security-event';

    /**
     * Configure route collection
     */
    protected function configureRouteCollection(RouteCollectionInterface $collection): void
    {
        $collection
            ->remove('create')
            ->remove('delete')
            ->remove('edit');
    }

    /**
     * Fields to be shown on lists
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', null, ['label' => 'ID'])
            ->add('user', null, [
                'label' => 'User',
                'admin_code' => 'sonata.admin.user.user'
            ])
            ->add('eventType', null, [
                'label' => 'Event Type',
                'template' => '@ProjetNormandieUser/Admin/security_event_type.html.twig'
            ])
            ->add('ipAddress', null, ['label' => 'IP Address'])
            ->add('createdAt', 'datetime', [
                'label' => 'Date/Time',
                'format' => 'd/m/Y H:i:s'
            ])
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                ]
            ]);
    }

    /**
     * Fields to be shown on filters
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('id', null, ['label' => 'ID'])
            ->add('user', null, [
                'label' => 'User',
                'field_type' => ModelAutocompleteType::class,
                'field_options' => [
                    'property' => 'username',
                    'admin_code' => 'sonata.admin.user.user',
                    'callback' => function ($admin, $property, $value) {
                        $datagrid = $admin->getDatagrid();
                        $query = $datagrid->getQuery();
                        $query
                            ->andWhere($query->expr()->orX(
                                $query->expr()->like('username', ':username'),
                                $query->expr()->like('email', ':username')
                            ))
                            ->setParameter('username', '%' . $value . '%');
                    },
                ],
            ])
            ->add('eventType', StringFilter::class, [
                'label' => 'Event Type',
                'field_type' => ChoiceType::class,
                'field_options' => [
                    'choices' => [
                        'Password Changed' => 'password_change',
                        'Email Changed' => 'email_change',
                        'Login Success' => 'login_success',
                        'Login Failure' => 'login_failure',
                        'Account Locked' => 'account_locked',
                        'Registration' => 'registration',
                        'Password Reset' => 'password_reset',
                    ],
                    'required' => false,
                    'multiple' => false,
                    'expanded' => false,
                ],
            ])
            ->add('ipAddress', null, ['label' => 'IP Address'])
            ->add('userAgent', null, ['label' => 'User Agent'])
            ->add('createdAt', DateTimeFilter::class, [
                'label' => 'Date/Time',
                'field_type' => DateTimePickerType::class,
                'field_options' => [
                    'format' => 'yyyy-MM-dd HH:mm:ss',
                ],
            ]);
    }

    /**
     * Fields to be shown on show action
     */
    protected function configureShowFields(ShowMapper $show): void
    {
        $show
            ->with('Event Information', ['class' => 'col-md-6'])
            ->add('id', null, ['label' => 'ID'])
            ->add('eventType', null, [
                'label' => 'Event Type',
                'template' => '@ProjetNormandieUser/Admin/security_event_type.html.twig'
            ])
            ->add('createdAt', 'datetime', [
                'label' => 'Date/Time',
                'format' => 'd/m/Y H:i:s'
            ])
            ->end()
            ->with('User Information', ['class' => 'col-md-6'])
            ->add('user', null, [
                'label' => 'User',
                'admin_code' => 'sonata.admin.user.user'
            ])
            ->add('ipAddress', null, ['label' => 'IP Address'])
            ->add('userAgent', null, ['label' => 'User Agent'])
            ->end()
            ->with('Event Data', ['class' => 'col-md-12'])
            ->add('eventData', null, [
                'label' => 'Event Data',
                'template' => '@ProjetNormandieUser/Admin/security_event_data.html.twig'
            ])
            ->end();
    }
}