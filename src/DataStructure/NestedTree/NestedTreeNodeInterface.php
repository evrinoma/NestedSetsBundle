<?php

namespace Evrinoma\NestedSetsBundle\DataStructure\NestedTree;

use Evrinoma\NestedSetsBundle\DataStructure\Payload\PayloadInterface;

/**
 * Interface NestedTreeNodeInterface
 *
 * @package App\DataStructure\NestedTree
 */
interface NestedTreeNodeInterface
{
//region SECTION: Public
    public function closeLevel(): NestedTreeNodeInterface;

    public function addNode(PayLoadInterface $payLoad): NestedTreeNodeInterface;

    public function addLevelNode(PayLoadInterface $payLoad): NestedTreeNodeInterface;
//endregion Public
}