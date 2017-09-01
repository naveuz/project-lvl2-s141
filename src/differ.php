<?php

namespace Gendiff\differ;

use Funct\Collection;
use function Gendiff\parser\parseData;
use function Gendiff\lib\getData;
use function Gendiff\lib\getFileFormat;
use function Gendiff\formatter\outData;

function genDiff($format, $pathToFile1, $pathToFile2)
{
    $arrBefore = parseData(getData($pathToFile1), getFileFormat($pathToFile1));
    $arrAfter = parseData(getData($pathToFile2), getFileFormat($pathToFile2));

    $ast = getDiffArray($arrBefore, $arrAfter);

    return outData($ast, $format);
}

function getDiffArray(array $before, array $after)
{
    $keys = Collection\union(array_keys($before), array_keys($after));

    return array_reduce($keys, function ($acc, $key) use ($before, $after) {

        if (array_key_exists($key, $before) && array_key_exists($key, $after)) {
            if (is_array($before[$key]) && is_array($after[$key])) {
                $acc[] = ['node' => $key,
                          'type' => 'parent',
                          'children' => getDiffArray($before[$key], $after[$key])];
            } else {
                if ($before[$key] !== $after[$key]) {
                    $acc[] = ['node' => $key,
                              'type' => 'changed',
                              'from' => $before[$key],
                              'to' => $after[$key]];
                } else {
                    $acc[] = ['node' => $key,
                              'type' => 'unchanged',
                              'from' => $before[$key],
                              'to' => $after[$key]];
                }
            }
        } elseif (array_key_exists($key, $before)) {
            $acc[] = ['node' => $key,
                      'type' => 'removed',
                      'from' => $before[$key],
                      'to' => null];
        } else {
            $acc[] = ['node' => $key,
                      'type' => 'added',
                      'from' => null,
                      'to' => $after[$key]];
        }

        return $acc;
    }, []);
}
