<?php

namespace ProjetNormandie\UserBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class GroupAdmin extends AbstractAdmin
{
    protected $baseRouteName = 'pnu_admin_group';

    /**
     * @param FormMapper $form
     */
    protected function configureFormFields(FormMapper $form): void
    {
        $form
            ->add('name', TextType::class, [
                'label' => 'group.form.name',
                'required' => false,
            ])
            ->add('roles', CollectionType::class, [
                'label' => 'group.form.roles',
                'required' => false,
                'allow_add' => true,
                'allow_delete' => true,
            ]);
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter): void
    {
        $filter
            ->add('name', null, ['label' => 'group.filter.name']);
    }

    /**
     * @param ListMapper $list
     */
    protected function configureListFields(ListMapper $list): void
    {
        $list
            ->addIdentifier('id', null, ['label' => 'group.list.id'])
            ->add('name', null, ['label' => 'group.list.name'])
            ->add('roles', null, ['label' => 'group.list.roles'])
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
            ->with('group_show_group', ['class' => 'col-md-12'])
            ->add('id', null, ['label' => 'group.show.id'])
            ->add('name', null, ['label' => 'group.show.name'])
            ->add('roles', null, ['label' => 'group.show.roles'])
            ->end();
    }
}
