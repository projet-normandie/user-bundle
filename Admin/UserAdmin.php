<?php
namespace ProjetNormandie\UserBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use Sonata\AdminBundle\Form\Type\ModelListType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use ProjetNormandie\UserBundle\Entity\User;

class UserAdmin extends AbstractAdmin
{
    /**
     * @param FormMapper $form
     */
    protected function configureFormFields(FormMapper $form)
    {
        $form
            ->with('User', ['class' => 'col-md-6'])
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
            ])
            ->end()
            ->with('Personal', ['class' => 'col-md-6'])
            ->add('firstName', TextType::class, [
                'required' => false,
            ])
            ->add('lastName', TextType::class, [
                'required' => false,
            ])
            ->add('address', TextType::class, [
                'required' => false,
            ])
            ->add('birthDate', DateType::class, [
                'label' => 'Birth Date',
                'required' => false,
                'years' => range(1900, date('Y'))
            ])
            ->add('timeZone')
            ->add('gender', ChoiceType::class, [
                'expanded' => true,
                'multiple' => false,
                'choices' => [
                    'Undefined' => User::GENDER_UNDEFINED,
                    'Male' => User::GENDER_MALE,
                    'Female' => User::GENDER_FEMALE,
                ]
            ])
            ->end()
            ->with('Communication')
            ->add('personalWebsite', TextType::class, [
                'required' => false,
            ])
            ->add('facebook', TextType::class, [
                'required' => false,
            ])
            ->add('twitter', TextType::class, [
                'required' => false,
            ])
            ->add('googleplus', TextType::class, [
                'required' => false,
            ])
            ->add('youtube', TextType::class, [
                'required' => false,
            ])
            ->add('dailymotion', TextType::class, [
                'required' => false,
            ])
            ->add('twitch', TextType::class, [
                'required' => false,
            ])
            ->add('skype', TextType::class, [
                'required' => false,
            ])
            ->add('snapchat', TextType::class, [
                'required' => false,
            ])
            ->add('pinterest', TextType::class, [
                'required' => false,
            ])
            ->add('trumblr', TextType::class, [
                'required' => false,
            ])
            ->add('blogger', TextType::class, [
                'required' => false,
            ])
            ->add('reddit', TextType::class, [
                'required' => false,
            ])
            ->add('deviantart', TextType::class, [
                'required' => false,
            ])
            ->end();
    }

    /**
     * @param DatagridMapper $filter
     */
    protected function configureDatagridFilters(DatagridMapper $filter)
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
    protected function configureListFields(ListMapper $list)
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
    protected function configureShowFields(ShowMapper $show)
    {
        $show
            ->with('User')
            ->add('id')
            ->add('username')
            ->add('email')
            ->add('enabled')
            ->add('locale')
            ->add('nbConnexion')
            ->add('lastLogin')
            ->end()
            ->with('Personal')
            ->add('firstName')
            ->add('lastName')
            ->add('address')
            ->add('birthDate')
            ->add('gender')
            ->add('timeZone')
            ->end()
            ->with('Communication')
            ->add('personalWebsite')
            ->add('facebook')
            ->add('twitter')
            ->add('googleplus')
            ->add('youtube')
            ->add('dailymotion')
            ->add('twitch')
            ->add('skype')
            ->add('snapchat')
            ->add('pinterest')
            ->add('trumblr')
            ->add('blogger')
            ->add('reddit')
            ->add('deviantart')
            ->end()
            ->add('userIp');
    }

    /**
     * @param RouteCollection $collection
     */
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection
            ->remove('create')
            ->remove('export')
            ->remove('delete');
    }
}
