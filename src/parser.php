<?php

namespace Gendiff\parser;

use Symfony\Component\Yaml\Yaml;

function parseData($data, $format)
{
    switch ($format) {
        case 'json':
            return parseJson($data);

        case 'yml':
            return parseYaml($data);
    }
}

function parseJson($data)
{
    return json_decode($data, true);
}

function parseYaml($data)
{
    return Yaml::parse($data);
}
