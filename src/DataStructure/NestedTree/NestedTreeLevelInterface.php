<?php

namespace Evrinoma\NestedSetsBundle\DataStructure\NestedTree;

use Evrinoma\NestedSetsBundle\DataStructure\Payload\PayloadInterface;

/**
 * Interface NestedTreeLevelInterface
 *
 * @package App\DataStructure\NestedTree
 */
interface NestedTreeLevelInterface
{
//region SECTION: Public
    public function addNodeToLevel(PayLoadInterface $payLoad, $level):NestedTreeLevelInterface;
//endregion Public
}