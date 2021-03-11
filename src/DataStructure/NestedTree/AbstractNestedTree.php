<?php

namespace Evrinoma\NestedSetsBundle\DataStructure\NestedTree;

use Evrinoma\NestedSetsBundle\DataStructure\Payload\PayloadInterface;
use Exception;

/**
 * Class AbstractNestedTree
 *
 * @package App\DataStructure\NestedTree
 */
abstract class AbstractNestedTree implements NestedTreeInterface
{
//region SECTION: Fields
    public const LEVEL    = 'level';
    public const LEFT     = 'lft';
    public const RIGHT    = 'rgt';
    public const IS_LEAF  = 'isLeaf';
    public const EXPANDED = 'expanded';
    public const LOADED   = 'loaded';

    private const INFINITY_LOOP = 127;
    /**
     * указатель на root
     *
     * @var null|NestedTree
     */
    protected $head;
    /**
     * указатель на родитель
     *
     * @var null|NestedTree
     */
    protected $parent;
    /**
     * массив дочерних элементов родителя
     *
     * @var NestedTree[]
     */
    protected $childs = [];
    /**
     * указатель на текущий нод
     *
     * @var null|NestedTree
     */
    protected $pointer;
    /**
     * указатель на последний добавленный нод
     *
     * @var NestedTree[]
     */
    protected $lasts = [];
    /**
     * указатель на последний добавленный нод
     *
     * @var null|NestedTree
     */
    protected $last;
    /**
     * уровень глубины текущего узла, для ROOT узла level всегда равен -1
     *
     * @var int
     */
    protected $level = -1;
    /**
     * массив элементов по уровням
     *
     * @var NestedTree[][]
     */
    protected $payLoadByLevel;
    /**
     * левая часть
     *
     * @var int
     */
    private $left = 0;
    /**
     * правая часть
     *
     * @var int
     */
    private $right = 0;
    /**
     * одиночный узел
     *
     * @var bool
     */
    private $isLeaf = true;
    /**
     * ветвь
     *
     * @var bool
     */
    private $expanded = false;
    /**
     * узел загружен узел
     *
     * @var bool
     */
    private $isLoaded = true;
    /**
     * максимальная глубина только у head узла
     *
     * @var int
     */
    private $maxLevel = 0;
    /**
     * полезная нагрузка для узел дерева
     *
     * @var PayLoadInterface
     */
    private $payLoad;
//endregion Fields

//region SECTION: Constructor
    /**
     * AbstractNestedTree constructor.
     *
     * @param bool                  $init
     * @param null                  $parent
     * @param PayLoadInterface|null $payLoad
     */
    public function __construct($init = true, $parent = null, ?PayLoadInterface $payLoad = null)
    {
        if ($init) {
            //это root дерева
            // указатель на текущую позицию
            $this->pointer = &$this;
            //указатель на root
            $this->head  = &$this;
            $this->level = -1;
            $this->left  = 0;
            $this->right = $this->left + 1;
            $this->maxLevel++;
            $this->isLeaf   = false;
            $this->expanded = true;
        } else {
            if ($parent) {
                $this->level       = $parent->level + 1;
                $lastParentElement = \count($parent->childs);
                if ($lastParentElement) {
                    $lastParentElement--;
                    $this->left = $parent->childs[$lastParentElement]->right + 1;
                } else {
                    $this->left = $parent->left + 1;
                }
                //   $this->left = $parent->left+1;
                $this->right   = $this->left + 1;
                $this->pointer = &$parent->pointer;
                //указатель на root
                $this->head = &$parent->head;
                //указатель на родителя
                $this->parent = &$parent;
                //так как у родителя появляется дочерний узел, то отмечаем родителя что он не одинойный узел
                $parent->isLeaf = false;
                $this->expanded = true;
                $this->payLoad  = $payLoad;
            }
        }
        //увеличиваем число уровней в root
        if ($parent && $parent->head !== null && $this->level > $parent->head->maxLevel) {
            $parent->head->maxLevel = $this->level;
        }
    }
//endregion Constructor

//region SECTION: Protected
    /**
     * @param NestedTreeInterface $item
     * @param PayLoadInterface    $payLoad
     *
     * @return NestedTreeInterface
     */
    protected function insertNodeAndClose(NestedTreeInterface $item, PayLoadInterface $payLoad): NestedTreeInterface
    {
        $item->addLevelNode($payLoad);
        $this->head->lasts[$this->pointer->level] = &$this->pointer;
        $this->head->last                         = &$this->pointer;
        $item->closeLevel();

        return $this;
    }

    /**
     * @param PayLoadInterface $payLoad
     *
     * @return NestedTree
     * @throws Exception
     */
    protected function &createNode(PayLoadInterface $payLoad): NestedTreeInterface
    {
        $child = new NestedTree(false, $this->pointer, $payLoad);
        //добавляем нового потомка к родителю
        $child->pushChildParent($this->pointer);
        $this->addPayLoad($child);

        return $child;
    }

    /**
     * @param $parent
     *
     * @throws Exception
     */
    protected function setParentRight($parent): void
    {
        $i = self::INFINITY_LOOP;
        while ($parent !== null) {
            $right = $parent->right;
            if ($parent->parent !== null) {
                $parent->parent->right = $right + 1;
            }
            $parent = &$parent->parent;
            $i--;
            if (!$i) {
                $parent = null;
                throw new Exception('loop infinity detected');
            }
        }
    }
//endregion Protected

//region SECTION: Public
    /**
     * @param bool $expand
     *
     * @return array
     */
    public function toArray($expand = false): array
    {
        return [
            self::LEVEL    => $this->level,
            self::LEFT     => $this->left,
            self::RIGHT    => $this->right,
            self::IS_LEAF  => $this->isLeaf,
            self::EXPANDED => $expand ? $this->expanded : false,
            self::LOADED   => $expand ? $this->isLoaded : true,
        ];
    }

    /**
     * Метод проверяет, является ли дерево пустым
     *
     * @return bool
     */
    public function isEmptyTree(): bool
    {
        $payload = $this->head->payLoadByLevel;
        if (!is_array($payload) || !\count($payload)) {
            return true;
        }
        foreach ($payload as $levelKey => $levelData) {
            if (
                is_array($levelData) && \count($levelData)
            ) {
                return false;
            }
        }

        return true;

    }

//endregion Public

//region SECTION: Private
    /**
     * @param $parent
     */
    private function pushChildParent($parent): void
    {
        $parent->childs[] = $this;
    }

    /**
     * @param NestedTreeInterface $child
     *
     * @throws Exception
     */
    private function addPayLoad(NestedTreeInterface $child): void
    {
        if ($this->head->payLoadByLevel === null
            || !array_key_exists($child->level, $this->head->payLoadByLevel)
            || !array_key_exists($child->payLoad->getIdentity(), $this->head->payLoadByLevel[$child->level])
        ) {
            $this->head->payLoadByLevel[$child->level][$child->payLoad->getIdentity()] = &$child;
        } else {
            throw new Exception('Duplicate key name on level '.$child->level.' (#'.($child->payLoad->getIdentity() ?? 'NULL').')');
        }
    }
//endregion Private

//region SECTION: Getters/Setters
    /**
     * @return int
     */
    public function getMaxLevel(): int
    {
        return $this->head->maxLevel ?? 0;
    }

    /**
     * @param      $level
     * @param      $idNode
     *
     * @return NestedTree
     */
    public function getNodeDataByLevelAndIdentity($level, $idNode): NestedTreeInterface
    {
        /** @var NestedTree $node */
        if ($this->head->payLoadByLevel !== null
            && $this->getMaxLevel() >= $level
            && array_key_exists($level, $this->head->payLoadByLevel)
            && array_key_exists($idNode, $this->head->payLoadByLevel[$level])
        ) {
            return $this->head->payLoadByLevel[$level][$idNode];
        }

        return new NestedTree();
    }
//endregion Getters/Setters
}