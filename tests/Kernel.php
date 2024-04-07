<?php

namespace ProjetNormandie\UserBundle\Tests;

use ApiPlatform\Symfony\Bundle\ApiPlatformBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Exception;
use League\FlysystemBundle\FlysystemBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\MonologBundle\MonologBundle;
use Symfony\Bundle\SecurityBundle\SecurityBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use ProjetNormandie\UserBundle\ProjetNormandieUserBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class Kernel extends BaseKernel
{
    use MicroKernelTrait {
        MicroKernelTrait::configureContainer as private configureContainerFromTrait;
        MicroKernelTrait::configureRoutes as private privateConfigureRoutesFromTrait;
    }

    /**
     * {@inheritDoc}
     * @see \Symfony\Component\HttpKernel\KernelInterface::registerBundles()
     */
    public function registerBundles(): iterable
    {

        return [
            new FrameworkBundle(),
            new ApiPlatformBundle(),
            new ProjetNormandieUserBundle(),
            new DoctrineBundle(),
            new SecurityBundle(),
            new FlysystemBundle(),
            new TwigBundle(),
            new MonologBundle()
        ];
    }

    /**
     * @param LoaderInterface $loader
     * @throws Exception
     */
    public function registerContainerConfiguration(LoaderInterface $loader): void
    {
        $loader->load(__DIR__ . '/config/packages.yaml');

        $loader->load(function (ContainerBuilder $container) {
            $container->loadFromExtension('framework', array(
                'router' => array(
                    'resource' => __DIR__ . '/config/routes.yaml'
                )
            ));
        });
    }
}
