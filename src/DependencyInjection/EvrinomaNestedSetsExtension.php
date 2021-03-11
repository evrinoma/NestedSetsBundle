<?php


namespace Evrinoma\NestedSetsBundle\DependencyInjection;


use Evrinoma\NestedSetsBundle\EvrinomaNestedSetsBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;


class EvrinomaNestedSetsExtension extends Extension
{
//region SECTION: Public
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }
//endregion Public

//region SECTION: Getters/Setters
    public function getAlias()
    {
        return EvrinomaNestedSetsBundle::NESTED_SETS_BUNDLE;
    }
//endregion Getters/Setters
}