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

    const PLAIN = <<<DOC
Property 'common.setting2' was removed
Property 'common.setting6' was removed
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: 'complex value'
Property 'group1.baz' was changed.From 'bas' to 'bars'
Property 'group2' was removed
Property 'group3' was added with value: 'complex value'

DOC;

    const JSON = '[{"node":"host","type":"unchanged","from":"hexlet.io","to":"hexlet.io"},{"node":"timeout","type":"changed","from":50,"to":20},{"node":"proxy","type":"removed","from":"123.234.53.22","to":null},{"node":"port","type":"added","from":null,"to":3306},{"node":"prot","type":"added","from":null,"to":"http"}]';

    /**
     * @dataProvider additionProvider
     */
    public function testGenDiff($expected, $format, $pathToFile1, $pathToFile2)
    {
        $this->assertEquals($expected, genDiff($format, $pathToFile1, $pathToFile2));
    }
    public function additionProvider()
    {
        return [[self::PLAIN,
                'plain',
                'tests/fixtures/before-recur.json',
                'tests/fixtures/after-recur.json'],
                [self::JSON,
                'json',
                'tests/fixtures/before.yml',
                'tests/fixtures/after.yml'],
                [self::PRETTY,
                'pretty',
                'tests/fixtures/before-recur.json',
                'tests/fixtures/after-recur.json']
        ];
    }
}
