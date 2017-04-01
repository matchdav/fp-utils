<?php

namespace matchdav\Fp;

class Types
{
    /**
     * @param $topic
     */
    public static function isPrimitive($topic)
    {
        return is_null($topic) || is_scalar($topic);
    }
    /**
     * @param $topic
     */
    public static function isAssociativeArray($topic)
    {
        return is_array($topic) && (sizeof(array_keys($topic)) === sizeof($topic));
    }
}
