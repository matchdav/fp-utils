<?php

namespace matchdav\Fp;

class Access
{
    /**
     * @param $keypath
     * @return mixed
     */
    private static function keypath($keypath)
    {
        if (is_string($keypath)) {
            return explode('.', $keypath);
        }
        return $keypath;
    }

    /**
     * @param mixed $topic
     * @param $keypath
     * @return mixed
     */
    public static function get($topic, $keypath)
    {
        if (Types::isPrimitive($topic)) {
            return;
        }
        $exploded = self::keypath($keypath);
        $temp = &$topic;
        foreach ($exploded as $key) {
            if (is_array($temp)) {
                if (!array_key_exists($key, $temp)) {
                    return null;
                }
                $temp = &$temp[$key];
                continue;
            }
            if (is_object($temp)) {
                if (method_exists($temp, $key) && Functional::arity([$temp, $key]) === 0) {
                    $temp = &$temp->$key();
                    continue;
                }
                $getter = "get" . ucfirst($key);
                if (method_exists($temp, $getter) && Functional::arity([$temp, $getter]) === 0) {
                    $temp = &$temp->$getter();
                    continue;
                }
                if (!property_exists($temp, $key)) {
                    return null;
                }
                $temp = &$temp->$key;
                continue;
            }
        }
        return $temp;
    }

    /**
     * @param $topic
     * @param $keypath
     * @param $value
     * @return mixed
     */
    public static function set(&$topic, $keypath, $value)
    {
        if (Types::isPrimitive($topic)) {
            return $topic;
        }
        $exploded = self::keypath($keypath);
        $temp = &$topic;
        foreach ($exploded as $key) {
            if (is_array($temp)) {
                if (!array_key_exists($key, $temp)) {
                    $temp[$key] = [];
                }
                $temp = &$temp[$key];
                continue;
            }
            if (is_object($temp)) {
                if (method_exists($temp, $key)) {
                    throw new Exception("Can't set properties on a function.");
                }
                if (!property_exists($temp, $key)) {
                    $temp->$key = (object) [];
                }
                $temp = &$temp->$key;
                continue;
            }
            throw new Exception("Can't set properties on a primitive.");
        }
        $temp = $value;
        unset($temp);
        return $topic;
    }

    /**
     * @param $topic
     * @param $keypath
     */
    public static function has($topic, $keypath)
    {
        if (Types::isPrimitive($topic)) {
            return false;
        }
        $exploded = self::keypath($keypath);
        $temp = &$topic;
        foreach ($exploded as $key) {
            if (Types::isPrimitive($temp)) {
                return false;
            }
            if (is_array($temp)) {
                if (!array_key_exists($key, $temp)) {
                    return false;
                }
                $temp = &$temp[$key];
                continue;
            }
            if (is_object($temp)) {
                if (method_exists($temp, $key) && Functional::arity([$temp, $key]) === 0) {
                    $temp = &$temp->$key();
                    continue;
                }
                if (!property_exists($temp, $key)) {
                    return false;
                }
                $temp = &$temp->$key;
                continue;
            }
        }
        return true;
    }

    /**
     * @param $topic
     * @param $key
     * @param $default
     * @return mixed
     */
    public static function result($topic, $key, $default = null)
    {
        $result = Functional::invoke($topic, $key);
        if (is_null($result)) {
            return $default;
        }
        return $result;
    }

}
