<?php

namespace Evrinoma\NestedSetsBundle\DataStructure\NestedTree;

use Evrinoma\NestedSetsBundle\DataStructure\Payload\PayloadInterface;
use Exception;

class NestedTree extends AbstractNestedTree implements NestedTreeNodeInterface, NestedTreeLevelInterface
{
//region SECTION: Public
    /**
     * добавляет узел с новым уровнем к текущему узлу и меняет текущий узел на созданный
     *
     * @param PayLoadInterface $payLoad
     *
     * @return NestedTreeNodeInterface
     * @throws Exception
     */
    public function addLevelNode(PayLoadInterface $payLoad): NestedTreeNodeInterface
    {
        //выставляем указатель на наш новый элемент
        $this->head->pointer = &$this->createNode($payLoad);

        return $this;
    }

    /**
     * добавляет узел с новым уровнем к текущему узлу
     *
     * @param $payLoad
     *
     * @return NestedTreeNodeInterface
     * @throws Exception
     */
    public function addNode(PayLoadInterface $payLoad): NestedTreeNodeInterface
    {
        $this->createNode($payLoad);

        return $this;
    }

    /**
     * @return NestedTreeNodeInterface
     * @throws Exception
     */
    public function closeLevel(): NestedTreeNodeInterface
    {
        $lastParentElement = \count($this->pointer->childs);
        $this->setParentRight($lastParentElement ? $this->pointer->childs[$lastParentElement - 1] : $this->head->pointer);
        $this->head->pointer = &$this->pointer->parent;

        return $this;
    }

    /**
     * @param PayLoadInterface $payLoad
     * @param                  $level
     *
     * @return NestedTreeLevelInterface
     */
    public function addNodeToLevel(PayLoadInterface $payLoad, $level): NestedTreeLevelInterface
    {
        if ($this->head->payLoadByLevel !== null) {
            if (array_key_exists($level, $this->head->payLoadByLevel)) {
                //уровень есть, надо восстановить указатель и добавить узел
                if ($this->head->last->level === $level) {
                    $item          = $this->head->last;
                    $item->pointer = &$item->parent;
                } else {
                    if ($this->head->last->level > $level) {
                        $item          = $this->head->lasts[$level];
                        $item->pointer = &$item->parent;
                    } else {
                        $item          = $this->head->lasts[$level - 1];
                        $item->pointer = &$item;
                    }
                }
                $this->insertNodeAndClose($item, $payLoad);
            } else {
                //уровня нет и его надо содать, надо восстановить указатель и добавить узел
                $item          = $this->lasts[$this->last->level];
                $item->pointer = &$item;
                $this->insertNodeAndClose($item, $payLoad);
            }
        } else {
            $this->insertNodeAndClose($this, $payLoad);
        }

        return $this;
    }
//endregion Public
}