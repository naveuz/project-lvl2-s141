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
        return [['{'.PHP_EOL.'   host: hexlet.io'.PHP_EOL.
                 ' + timeout: 20'.PHP_EOL.
                 ' - timeout: 50'.PHP_EOL.
                 ' - proxy: 123.234.53.22'.PHP_EOL.
                 ' + port: 3306'.PHP_EOL.
                 ' + prot: http'.PHP_EOL.
                '}',
                'pretty',
                'tests/fixtures/before.json',
                'tests/fixtures/after.json']
        ];
    }
}
