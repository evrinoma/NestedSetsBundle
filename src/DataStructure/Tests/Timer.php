<?php


namespace Evrinoma\NestedSetsBundle\DataStructure\Tests;


class Timer
{
//region SECTION: Fields
    /**
     * @var float
     */
    private static $start = .0;
    /**
     * @var float
     */
    private static $finish = .0;
//endregion Fields

//region SECTION: Public
    public static function start()
    {
        self::$start = microtime(true);
    }

    public static function finish()
    {
        self::$finish = microtime(true) - self::$start;
    }

    public static function startToString()
    {
        return 'Start time: ['.self::$start.'] sec.';
    }

    public static function finishToString()
    {
        return 'Execution time: ['.self::$finish.'] sec.';
    }
//endregion Public
}