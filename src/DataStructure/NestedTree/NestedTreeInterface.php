<?php

namespace Evrinoma\NestedSetsBundle\DataStructure\NestedTree;

interface NestedTreeInterface
{
//region SECTION: Getters/Setters
    public function getMaxLevel(): int;

    public function getNodeDataByLevelAndIdentity($level, $idNode): NestedTreeInterface;
//endregion Getters/Setters

}