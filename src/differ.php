<?php

namespace Gendiff\differ;

function genDiff($format, $pathToFile1, $pathToFile2)
{
    $arrBefore = jsonToArray($pathToFile1);
    $arrAfter = jsonToArray($pathToFile2);

    $arrDiff = getDiffArray($arrBefore, $arrAfter);

    return arrayToPretty($arrDiff);
}

function jsonToArray($filePath)
{
    return json_decode(file_get_contents($filePath), true);
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

    $result = array_reduce(array_keys($before), function ($acc, $key) use ($before, $after) {

        if (array_key_exists($key, $after)) {
            if ($before[$key] !== $after[$key]) {
                $acc["+ {$key}"] = $after[$key];
                $acc["- {$key}"] = $before[$key];
            } else {
                $acc["  $key"] = $before[$key];
            }
        } else {
            $acc["- {$key}"] = $before[$key];
        }

        return $acc;
    }, []);

    $added = array_reduce(array_keys($after), function ($acc, $key) use ($before, $after) {

        if (!array_key_exists($key, $before)) {
            $acc["+ {$key}"] = $after[$key];
        }

        return $acc;
    }, []);

    return array_merge($result, $added);
}
