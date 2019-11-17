<?php
namespace ProjetNormandie\UserBundle\Admin;

use A2lix\TranslationFormBundle\Form\Type\TranslationsType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CountryAdmin extends AbstractAdmin
{
    /**
     * @inheritDoc
     */
    protected function configureFormFields(FormMapper $form)
    {
         $form
            ->add('codeIso2', TextType::class, [
                'label' => 'ISO ALPHA 2',
                'required' => true,
            ])
            ->add('codeIso3', TextType::class, [
                'label' => 'ISO ALPHA 3',
                'required' => true,
            ])
            ->add('codeIsoNumeric', TextType::class, [
                'label' => 'ISO NUMERIC code',
                'required' => true,
            ])
            ->add('translations', TranslationsType::class, [
                'required' => true,
            ]);
    }
    /**
     * @inheritDoc
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
    {
        $filter
            ->add('codeIso3');
    }
    /**
     * @inheritDoc
     */
    protected function configureListFields(ListMapper $list)
    {
        $list
            ->addIdentifier('id')
            ->add('codeIso2')
            ->add('codeIso3')
            ->add('codeIsoNumeric')
            ->add('getName', null, ['label' => 'English name'])
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
            ->add('getName', null, ['label' => 'English name'])
            ->add('codeIso3')
            ->add('codeIso2')
            ->add('codeIsoNumeric');
    }
    /**
     * @inheritDoc
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('export')
            ->remove('delete');
    }
}
