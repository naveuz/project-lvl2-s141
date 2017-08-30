<?php

namespace Gendiff\Tests;

use \PHPUnit\Framework\TestCase;
use function Gendiff\differ\genDiff;

class DifferTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function testGenDiff($expected, $format, $pathToFile1, $pathToFile2)
    {
        $this->assertEquals($expected, genDiff($format, $pathToFile1, $pathToFile2));
    }
    public function additionProvider()
    {
        return [
            [
'{
   host: hexlet.io
 + timeout: 20
 - timeout: 50
 - proxy: 123.234.53.22
 + port: 3306
 + prot: http
}',
            'pretty',
            'tests/fixtures/before.json',
            'tests/fixtures/after.json']
        ];
    }
}
