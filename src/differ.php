<?php

namespace Gendiff\differ;

use Funct\Collection;
use Gendiff\parser;

function genDiff($format, $pathToFile1, $pathToFile2)
{
    $arrBefore = parser\parseFile($pathToFile1);
    $arrAfter = parser\parseFile($pathToFile2);

    $arrDiff = getDiffArray($arrBefore, $arrAfter);

    return arrayToPretty($arrDiff);
}

function getDiffArray(array $before, array $after)
{
    $keys = Collection\union(array_keys($before), array_keys($after));

    return array_reduce($keys, function ($acc, $key) use ($before, $after) {

        if (array_key_exists($key, $before) && array_key_exists($key, $after)) {
            if (is_array($before[$key]) && is_array($after[$key])) {
                $acc[$key] = getDiffArray($before[$key], $after[$key]);
            } else {
                if ($before[$key] !== $after[$key]) {
                    $acc["+ {$key}"] = $after[$key];
                    $acc["- {$key}"] = $before[$key];
                } else {
                    $acc["  $key"] = $before[$key];
                }
            }
        } elseif (array_key_exists($key, $before)) {
            $acc["- {$key}"] = $before[$key];
        } else {
            $acc["+ {$key}"] = $after[$key];
        }

        return $acc;
    }, []);
}

function arrayToPretty(array $data)
{
    $pretty = array_map(function ($key, $value) {
        return " {$key}: {$value}" . PHP_EOL;
    }, array_keys($data), $data);

    return "{" . PHP_EOL .join('', $pretty) . "}";
}
