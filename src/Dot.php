<?php

namespace Dot;

use ReflectionClass;

abstract class Dot
{
    static function redirect(string $url)
    {
        if (\headers_sent()) {
            Js::redirect($url);
        } else {
            \header('location:' . $url);
        }
    }

    static function back()
    {
        $referer = $_SERVER['HTTP_REFERER'] ?? '/';
        self::redirect($referer);
    }

    static function turn()
    {

    }

    static function getTraits($className_or_object)
    {
        $className = is_string($className_or_object) ?
            $className_or_object :
            get_class($className_or_object);

        try {
            $class = new ReflectionClass($className);
            $traits = $class->getTraits();
            while ($parent = $class->getParentClass()) {
                $traits += $class->getTraits();
                $class = $parent;
            }
            return array_combine(array_keys($traits), array_keys($traits));
        } catch (\ReflectionException $e) {
            return null;
        }
    }
}