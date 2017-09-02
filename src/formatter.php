<?php

namespace Gendiff\formatter;

use Funct\Collection;

function outData(array $data, $format)
{
    switch ($format) {
        case 'pretty':
            return astToPretty($data);

        case 'plain':
            return astToPlain($data);

        case 'json':
            return astToJson($data);
    }
}

function astToJson(array $ast)
{
    return json_encode($ast);
}

function astToPlain(array $ast, $parent = '')
{
    $plain = array_map(function ($element) use ($parent) {

        switch ($element['type']) {
            case 'parent':
                $parent .= "{$element['node']}.";
                return astToPlain($element['children'], $parent);

            case 'changed':
                return "Property '{$parent}{$element['node']}' was changed.".
                    "From '{$element['from']}' to '{$element['to']}'".PHP_EOL;

            case 'added':
                $value = is_array($element['to']) ? 'complex value' : $element['to'];
                return "Property '{$parent}{$element['node']}' was added with value: '{$value}'".PHP_EOL;

            case 'removed':
                return "Property '{$parent}{$element['node']}' was removed".PHP_EOL;
        }
    }, $ast);

    return join('', $plain);
}

function astToPretty(array $ast, $lvl = 1)
{
    $pretty = array_map(function ($element) use ($lvl) {

        switch ($element['type']) {
            case 'parent':
                return [genIndent($lvl)." \"{$element['node']}\": {".PHP_EOL.
                    astToPretty($element['children'], $lvl + 1) .PHP_EOL.genIndent($lvl)." }"];

            case 'unchanged':
                return [genIndent($lvl)." \"{$element['node']}\": \"{$element['from']}\""];

            case 'changed':
                return [[genIndent($lvl)."-\"{$element['node']}\": \"{$element['from']}\""],
                       [genIndent($lvl)."+\"{$element['node']}\": \"{$element['to']}\""]];

            case 'added':
                if (is_array($element['to'])) {
                    return [genIndent($lvl)."+\"{$element['node']}\": {".PHP_EOL.
                        getElements($element['to'], $lvl + 1) .PHP_EOL.genIndent($lvl)." }"];
                }
                return [genIndent($lvl)."+\"{$element['node']}\": \"{$element['to']}\""];

            case 'removed':
                if (is_array($element['from'])) {
                    return [genIndent($lvl)."-\"{$element['node']}\": {".PHP_EOL.
                        getElements($element['from'], $lvl + 1) .PHP_EOL.genIndent($lvl)." }"];
                }
                return [genIndent($lvl)."-\"{$element['node']}\": \"{$element['from']}\""];
        }
    }, $ast);

    return join(PHP_EOL, Collection\flattenAll($pretty));
}

function genIndent($lvl)
{
    return str_repeat(' ', $lvl * 2);
}

function getElements(array $array, $lvl)
{
    $arr = array_map(function ($key, $value) use ($lvl) {
        return genIndent($lvl)." \"{$key}\": \"{$value}\"";
    }, array_keys($array), $array);

    return join(PHP_EOL, Collection\flattenAll($arr));
}
