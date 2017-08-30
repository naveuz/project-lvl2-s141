<?php

namespace Gendiff\parser;

use Symfony\Component\Yaml\Yaml;

function parseJson($filePath)
{
    return json_decode(file_get_contents($filePath), true);
}

function parseYaml($filePath)
{
    return Yaml::parse(file_get_contents($filePath));
}
