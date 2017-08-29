<?php

namespace Gendiff\Tests;

use \PHPUnit\Framework\TestCase;
use function Gendiff\differ\getDiffArray;

class DifferTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */
    public function testGetDiffArray($expected, $before, $after)
    {
        $this->assertEquals($expected, getDiffArray($before, $after));
    }
    public function additionProvider()
    {
        return [
            [["  host" => "hexlet.io",
              "+ timeout" => 20,
              "- timeout" => 50,
              "- proxy" => "123.234.53.22",
              "+ verbose" => true
            ],
            ["host" => "hexlet.io",
             "timeout" => 50,
             "proxy" => "123.234.53.22"
            ],
            ["timeout" => 20,
             "verbose" => true,
             "host" => "hexlet.io"
            ]]
        ];
    }
}
