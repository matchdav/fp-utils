<?php

namespace matchdav\Fp;

class Collections
{
    /**
     * @param $topic
     */
    public static function isIterable($topic)
    {
        return is_array($topic) || is_object($topic) || $topic instanceof \stdClass || $topic instanceof \Iterator;
    }
    /**
     * @param $topic
     * @return mixed
     */
    public static function keys($topic)
    {
        $keys = [];
        if (!self::isIterable($topic)) {
            return $keys;
        }
        foreach ($topic as $key => $value) {
            $keys[] = $key;
        }
        return $keys;
    }
    /**
     * @param $topic
     * @return mixed
     */
    public static function values($topic)
    {
        $keys = [];
        if (!self::isIterable($topic)) {
            return $keys;
        }
        foreach ($topic as $key => $value) {
            $keys[] = $value;
        }
        return $keys;
    }
    /**
     * @param $topic
     * @param $callable
     * @return mixed
     */
    public static function map($topic, $callable)
    {
        $result = [];
        if (!self::isIterable($topic)) {
            return $result;
        }
        foreach ($topic as $key => $value) {
            $result[$key] = $callable($value);
        }
        if (is_object($topic) && !empty($topic)) {
            return (object) $result;
        }
        return $result;
    }
    /**
     * @param $topic
     * @return mixed
     */
    public static function first($topic)
    {
        $result = null;
        foreach ($topic as $key => $value) {
            $result = $value;
            break;
        }
        return $result;
    }
}
