<?php
namespace ProjetNormandie\UserBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\Form\Type\CollectionType;
use ProjetNormandie\UserBundle\Entity\Ip;

class IpAdmin extends AbstractAdmin
{
    /**
     * @inheritDoc
     */
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->add('label', TextType::class, [
                'label' => 'Label',
                'required' => false,
            ])
            ->add(
                'status',
                ChoiceType::class,
                [
                    'label' => 'Etat',
                    'choices' => Ip::getStatusChoices(),
                ]
            );
    }
    /**
     * @inheritDoc
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('label');
    }
    /**
     * @inheritDoc
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('label')
            ->add('status')
            ->add('_action', 'actions', [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                ]
            ]);
    }
    /**
     * @inheritDoc
     */
    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->add('id')
            ->add('label')
            ->add('status')
            ->add('userIp');
    }
}
