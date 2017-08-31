<?php

namespace Gendiff\lib;

function getData($filePath)
{
    return file_get_contents($filePath);
}

function getFileFormat($pathToFile)
{
    $fileInfo = new \SplFileInfo($pathToFile);
    return $fileInfo->getExtension();
}
