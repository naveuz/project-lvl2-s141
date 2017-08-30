<?php

namespace Gendiff\differ;

use Funct\Collection;
use Gendiff\parser;

function genDiff($format, $pathToFile1, $pathToFile2)
{
    $arrBefore = parser\parseJson($pathToFile1);
    $arrAfter = parser\parseJson($pathToFile2);

    $arrDiff = getDiffArray($arrBefore, $arrAfter);

    return arrayToPretty($arrDiff);
}

function arrayToPretty(array $data)
{
    $pretty = array_map(function ($key, $value) {
        return " {$key}: {$value}" . PHP_EOL;
    }, array_keys($data), $data);

    return "{" . PHP_EOL . join('', $pretty) . "}";
}

function getDiffArray(array $before, array $after)
{
    $keys = Collection\union(array_keys($before), array_keys($after));

    return array_reduce($keys, function ($acc, $key) use ($before, $after) {

        if (array_key_exists($key, $before) && array_key_exists($key, $after)) {
            if ($before[$key] !== $after[$key]) {
                $acc["+ {$key}"] = $after[$key];
                $acc["- {$key}"] = $before[$key];
            } else {
                $acc["  $key"] = $before[$key];
            }
        } elseif (array_key_exists($key, $before)) {
            $acc["- {$key}"] = $before[$key];
        } else {
            $acc["+ {$key}"] = $after[$key];
        }

        return $acc;
    }, []);
}
