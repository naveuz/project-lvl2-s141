<?php

namespace Gendiff\formatter;

function outData(array $data, $format)
{
    switch ($format) {
        case 'pretty':
            return astToPretty($data);

        default:
            # code...
            break;
    }
}

function astToPretty(array $ast, $lvl = 1)
{
    switch ($lvl) {
        case '1':
            $t = '  ';
            break;
        case '2':
            $t = '    ';
            break;
        case '3':
            $t = '      ';
            break;
    }
    $pretty = array_map(function ($element) use ($lvl, $t) {

        switch ($element['type']) {
            case 'parent':
                return "{$t} \"{$element['node']}\": {" .
                    astToPretty($element['children'], $lvl + 1) . "{$t} }".PHP_EOL;

            case 'unchanged':
                return "{$t} \"{$element['node']}\": \"{$element['from']}\"".PHP_EOL;

            case 'changed':
                return "{$t}-\"{$element['node']}\": \"{$element['from']}\"".PHP_EOL.
                       "{$t}+\"{$element['node']}\": \"{$element['to']}\"".PHP_EOL;

            case 'added':
                if (is_array($element['to'])) {
                    return "{$t}+\"{$element['node']}\": {".PHP_EOL.
                        getElements($element['to'], $t) . "{$t} }".PHP_EOL;
                }
                return "{$t}+\"{$element['node']}\": \"{$element['to']}\"".PHP_EOL;

            case 'removed':
                if (is_array($element['from'])) {
                    return "{$t}-\"{$element['node']}\": {".PHP_EOL.
                        getElements($element['from'], $t) . "{$t} }".PHP_EOL;
                }
                return "{$t}-\"{$element['node']}\": \"{$element['from']}\"".PHP_EOL;
        }
    }, $ast);

    return PHP_EOL . join('', $pretty);
}

function getElements(array $array, $t)
{
    $arr = array_map(function ($key, $value) use ($t) {
        return "{$t}   \"{$key}\": \"{$value}\"".PHP_EOL;
    }, array_keys($array), $array);
    return join('', $arr);
}
