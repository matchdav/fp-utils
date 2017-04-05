<?php

namespace matchdav\Fp;

class Functional
{
    /**
     * @param $method
     * @param $length
     * @return mixed
     */
    public static function arity(callable $method, $length = null)
    {
        if (is_array($method)) {
            $r = new \ReflectionClass($method[0]);
            $func = $r->getMethod($method[1]);
            $arity = $func->getNumberOfRequiredParameters();
        }
        if (!isset($arity)) {
            $r = new \ReflectionFunction($method);
            $arity = $r->getNumberOfRequiredParameters();
        }
        if (is_numeric($length) && $length > $arity) {
            return $length;
        }
        return $arity;
    }
    /**
     * @param $args
     * @param $reverse
     * @return mixed
     */
    private static function methodsArray($args, $reverse = false)
    {
        if (sizeof($args) === 1 && is_array($args[0])) {
            //  [[method1, method2]]
            $args = $args[0];
        }
        $args = array_filter($args, 'is_callable');
        if ($reverse) {
            array_reverse($args);
        }
        return $args;
    }
    /**
     * @param $methods
     * @return mixed
     */
    private static function pipeline($methods)
    {
        return function ($result) use ($methods) {
            foreach ($methods as $method) {
                $result = $method($result);
            }
            return $result;
        };
    }
    public static function compose()
    {
        return self::pipeline(self::methodsArray(func_get_args(), true));
    }

    public static function flow()
    {
        return self::pipeline(self::methodsArray(func_get_args(), false));
    }

    /**
     * @param $method
     */
    public static function partial(callable $method)
    {
        $args = array_slice(func_get_args(), 1);
        $arity = self::arity($method);
        $remaining = $arity - sizeof($args);
        if (sizeof($args) === $arity) {
            return \call_user_func_array($method, $args);
        }
        return function () use ($method, $args, $remaining) {
            $invokeArgs = array_slice(func_get_args(), 0, $remaining);
            return \call_user_func_array($method, array_merge($args, $invokeArgs));
        };
    }

    /**
     * @param $method
     */
    public static function partialRight(callable $method)
    {
        $args = array_slice(func_get_args(), 1);
        $arity = self::arity($method);
        $remaining = $arity - sizeof($args);
        if (sizeof($args) === $arity) {
            return \call_user_func_array($method, $args);
        }
        return function () use ($method, $args, $remaining) {
            $invokeArgs = array_slice(func_get_args(), 0, $remaining);
            return \call_user_func_array($method, array_merge($invokeArgs, $args));
        };

    }

    /**
     * @param $method
     * @param $length
     * @return mixed
     */
    public static function curry(callable $method, $length = null)
    {
        $arity = self::arity($method, $length);
        $args = array_slice(func_get_args(), 1);
        $curried = function ($props) use ($method, $arity, $args, &$curried) {
            if (!is_array($props)) {
                $props = (array) $props;
            }
            $newArgs = array_merge($args, $props);
            if ($arity <= sizeof($newArgs)) {
                return \call_user_func_array($method, $newArgs);
            }
            return function ($prop) use ($props, $curried) {
                $props = array_merge($props,(array) $prop);
                return $curried($props);
            };
        };
        return $curried;
    }

    /**
     * @param $method
     * @param $length
     * @return mixed
     */
    public static function curryRight(callable $method, $length = null)
    {
        $arity = self::arity($method, $length);
        $args = array_slice(func_get_args(), 1);
        $curried = function ($props) use ($method, $arity, $args, &$curried) {
            if (!is_array($props)) {
                $props = (array) $props;
            }
            $newArgs = array_merge($props, $args);
            if ($arity <= sizeof($newArgs)) {
                return \call_user_func_array($method, $newArgs);
            }
            return function ($prop) use ($props, $curried) {
                array_unshift($props, $prop);
                return $curried($props);
            };
        };
        return $curried;
    }

    /**
     * @param $topic
     * @param $key
     * @return null
     */
    public static function invoke($topic, $key)
    {
        if (is_array($topic) && array_key_exists($key, $topic)) {
            return $topic[$key];
        }
        if (is_object($topic) && property_exists($topic, $key)) {
            return $topic->$key;
        }
        $callable = [$topic, $key];
        $args = array_slice(func_get_args(), 2);
        if (!is_callable($callable) || self::arity($callable) > sizeof($args)) {
            return;
        }
        return call_user_func_array($callable, $args);
    }

}
