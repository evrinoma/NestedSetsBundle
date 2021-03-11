<?php


namespace Evrinoma\NestedSetsBundle;



use Evrinoma\NestedSetsBundle\DependencyInjection\EvrinomaNestedSetsExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class EvrinomaNestedSetsBundle extends Bundle
{
    public const NESTED_SETS_BUNDLE = 'nested-sets';

//region SECTION: Getters/Setters
    public function getContainerExtension()
    {
        if (null === $this->extension) {
            $this->extension = new EvrinomaNestedSetsExtension();
        }

        return $this->extension;
    }
//endregion Getters/Setters
}