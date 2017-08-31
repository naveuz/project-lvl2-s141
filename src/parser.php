<?php

namespace Gendiff\parser;

use Symfony\Component\Yaml\Yaml;
use function Gendiff\lib\getContent;

function parseFile($pathToFile)
{
    $fileInfo = new \SplFileInfo($pathToFile);
    $extension = $fileInfo->getExtension();

    switch ($extension) {
        case 'json':
            return parseJson($pathToFile);

        case 'yml':
            return parseYaml($pathToFile);
    }
}

function parseJson($filePath)
{
    return json_decode(getContent($filePath), true);
}

function parseYaml($filePath)
{
    return Yaml::parse(getContent($filePath));
}
