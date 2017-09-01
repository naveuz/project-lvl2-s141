<?php

namespace Gendiff\Tests;

use \PHPUnit\Framework\TestCase;
use function Gendiff\differ\genDiff;

class DifferTest extends TestCase
{
    const PRETTY = <<<DOC

   "common": {
     "setting1": "Value 1"
    -"setting2": "200"
     "setting3": "1"
    -"setting6": {
       "key": "value"
     }
    +"setting4": "blah blah"
    +"setting5": {
       "key5": "value5"
     }
   }
   "group1": {
    -"baz": "bas"
    +"baz": "bars"
     "foo": "bar"
   }
  -"group2": {
     "abc": "12345"
   }
  +"group3": {
     "fee": "100500"
   }

DOC;
    /**
     * @dataProvider additionProvider
     */
    public function testGenDiff($expected, $format, $pathToFile1, $pathToFile2)
    {
        $this->assertEquals($expected, genDiff($format, $pathToFile1, $pathToFile2));
    }
    public function additionProvider()
    {
        return [[PHP_EOL.'   "host": "hexlet.io"'.PHP_EOL.
                 '  -"timeout": "50"'.PHP_EOL.
                 '  +"timeout": "20"'.PHP_EOL.
                 '  -"proxy": "123.234.53.22"'.PHP_EOL.
                 '  +"port": "3306"'.PHP_EOL.
                 '  +"prot": "http"'.PHP_EOL,
                'pretty',
                'tests/fixtures/before.json',
                'tests/fixtures/after.json'],
                [PHP_EOL.'   "host": "hexlet.io"'.PHP_EOL.
                 '  -"timeout": "50"'.PHP_EOL.
                 '  +"timeout": "20"'.PHP_EOL.
                 '  -"proxy": "123.234.53.22"'.PHP_EOL.
                 '  +"port": "3306"'.PHP_EOL.
                 '  +"prot": "http"'.PHP_EOL,
                'pretty',
                'tests/fixtures/before.yml',
                'tests/fixtures/after.yml'],
                [self::PRETTY,
                'pretty',
                'tests/fixtures/before-recur.json',
                'tests/fixtures/after-recur.json']
        ];
    }
}
