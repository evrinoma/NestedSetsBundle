<?php

namespace Evrinoma\NestedSetsBundle\DataStructure\Payload;

class Payload implements PayloadInterface
{
//region SECTION: Fields
    /**
     * уникальный идентификатор для каждой строки
     *
     * @var null
     */
    private $identity;

    /**
     * набор передаваемых данных в узел дерева
     *
     * @var null
     */
    private $payLoad ;

    /**
     * загрузка по клику подстроки
     *
     * @var null
     */
    private $needLoad = false;
//endregion Fields

//region SECTION: Constructor
    /**
     * Payload constructor.
     *
     * @param $id
     */
    public function __construct($id)
    {
        $this->setIdentity($id);
    }
//endregion Constructor

//region SECTION: Getters/Setters
    /**
     * @return null
     */
    public function getIdentity()
    {
        return $this->identity;
    }

    /**
     * @return null
     */
    public function getPayLoad()
    {
        return $this->payLoad;
    }

    /**
     * @return null
     */
    public function getNeedLoad()
    {
        return $this->needLoad;
    }

    /**
     * @param null $identity
     */
    public function setIdentity($identity)
    {
        $this->identity = $identity;
    }

    /**
     * @param null $payLoad
     */
    public function setPayLoad($payLoad)
    {
        $this->payLoad = $payLoad;
    }

    /**
     * @param null $needLoad
     */
    public function setNeedLoad($needLoad)
    {
        $this->needLoad = $needLoad;
    }
//endregion Getters/Setters

}