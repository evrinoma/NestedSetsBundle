<?php


namespace Evrinoma\NestedSetsBundle\DataStructure\Tests;


use Evrinoma\NestedSetsBundle\DataStructure\NestedTree\NestedTree;
use Evrinoma\NestedSetsBundle\DataStructure\NestedTree\NestedTreeInterface;
use Evrinoma\NestedSetsBundle\DataStructure\Payload\Payload;
use Exception;
use Generator;

class NestedTreeTest
{
//region SECTION: Fields
    /**
     * число узлов 1124996
     * NODE_COUNT = 10
     * MAX_DEEP = 10
     * 19.914458036423 sec
     * 361496.552kB
     */
    /**
     * число узлов генерируется случайно для выбранного уровня
     */
    private const NODE_COUNT = 15;
    /**
     * число уровней
     */
    private const MAX_DEEP  = 10;
    private const MAX_LEVEL = self::MAX_DEEP - 1;
    /**
     * случаная составляющая числа узлов
     */
    private $randomCountNode = false;
    /**
     * идентификатор записи
     *
     * @var int
     */
    private $id = 0;
    /**
     * уровень списка
     *
     * @var int
     */
    private $level = 0;
//endregion Fields

//region SECTION: Public
    /**
     * @throws Exception
     */
    public function test()
    {
        $timer = new Timer();

        $timer::start();

        $memory = memory_get_usage();

        $nestedTree = $this->setRandomCountNode()->geenerateNestedTree(new NestedTree());

        $memoryUsed = (memory_get_usage() - $memory) / 1000;

        $timer::finish();

        return ['timer' => [$timer::startToString(), $timer::finishToString()], 'nestedTree' => $nestedTree, 'memory' => $memoryUsed.'kB'];
        // throw new Exception('HALT');
    }


    /**1
     * @param array $dataSrc
     * @param array $dataDst
     *
     * @throws Exception
     */
    public function compareGetNodeData(array $dataSrc, array $dataDst): void
    {
        foreach ($dataSrc as $key => $value) {
            if ($dataDst[$key] !== $value) {
                throw new Exception('HALT');
            }
        }
    }
//endregion Public

//region SECTION: Private
    /**
     * @param NestedTreeInterface $nestedTree
     *
     * @return NestedTreeInterface
     * @throws Exception
     */
    private function geenerateNestedTree(NestedTreeInterface $nestedTree): NestedTreeInterface
    {
        $levelCount = $this->randomCountNode ? random_int(1, self::NODE_COUNT) : self::NODE_COUNT;
        $level      = $this->generatePayload($levelCount);

        foreach ($level as $value) {
            $nestedTree->addNodeToLevel($value, $this->level);
            if ($this->level < self::MAX_LEVEL) {
                $this->level++;
                $this->geenerateNestedTree($nestedTree);
                $this->level--;
            }
        }

        return $nestedTree;
    }

    /**
     * @param $count
     *
     * @return Generator|null
     */
    private function generatePayload($count): ?Generator
    {
        for ($i = 0; $i < $count; $i++) {
            yield new Payload($this->id++);
        }
    }
//endregion Private

//region SECTION: Getters/Setters
    /**
     * @param bool $randomCountNode
     *
     * @return NestedTreeTest
     */
    public function setRandomCountNode(bool $randomCountNode = true): self
    {
        $this->randomCountNode = $randomCountNode;

        return $this;
    }
//endregion Getters/Setters
}