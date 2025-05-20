<?php

namespace ProjetNormandie\UserBundle\Admin;

use ProjetNormandie\UserBundle\Security\Event\SecurityEventTypeEnum;
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
    protected function configureRoutes(RouteCollectionInterface $collection): void
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
            ->addIdentifier('id', null, ['label' => 'security_event.list.id'])
            ->add('user', null, [
                'label' => 'security_event.list.user',
                'admin_code' => 'sonata.admin.user.user'
            ])
            ->add('eventType', null, [
                'label' => 'security_event.list.event_type',
                'template' => '@ProjetNormandieUser/Admin/security_event_type.html.twig'
            ])
            ->add('ipAddress', null, ['label' => 'security_event.list.ip_address'])
            ->add('createdAt', 'datetime', [
                'label' => 'security_event.list.date_time',
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
            ->add('id', null, ['label' => 'security_event.filter.id'])
            ->add('user', null, [
                'label' => 'security_event.filter.user',
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
                'label' => 'security_event.filter.event_type',
                'field_type' => ChoiceType::class,
                'field_options' => [
                    'choices' => SecurityEventTypeEnum::getOptionsForForm(),
                    'required' => false,
                    'multiple' => false,
                    'expanded' => false,
                    'translation_domain' => 'messages',
                ],
            ])
            ->add('ipAddress', null, ['label' => 'security_event.filter.ip_address'])
            ->add('userAgent', null, ['label' => 'security_event.filter.user_agent'])
            ->add('createdAt', DateTimeFilter::class, [
                'label' => 'security_event.filter.date_time',
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
            ->with('security_event_show_event_information', ['class' => 'col-md-6'])
            ->add('id', null, ['label' => 'security_event.list.id'])
            ->add('eventType', null, [
                'label' => 'security_event.list.event_type',
                'template' => '@ProjetNormandieUser/Admin/security_event_type.html.twig'
            ])
            ->add('createdAt', 'datetime', [
                'label' => 'security_event.list.date_time',
                'format' => 'd/m/Y H:i:s'
            ])
            ->end()
            ->with('security_event_show_user_information', ['class' => 'col-md-6'])
            ->add('user', null, [
                'label' => 'security_event.list.user',
                'admin_code' => 'sonata.admin.user.user'
            ])
            ->add('ipAddress', null, ['label' => 'security_event.list.ip_address'])
            ->add('userAgent', null, ['label' => 'security_event.filter.user_agent'])
            ->end()
            ->with('security_event_show_event_data', ['class' => 'col-md-12'])
            ->add('eventData', null, [
                'label' => 'security_event.show.event_data',
                'template' => '@ProjetNormandieUser/Admin/security_event_data.html.twig'
            ])
            ->end();
    }
}
