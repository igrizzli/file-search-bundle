<?php
namespace Vilks\FileSearchBundle\Tests\Unit\Engine\PhpRead;

use Vilks\FileSearchBundle\Engine\PhpRead\PhpReadEngine;

class PhpReadEngineTest extends \PHPUnit_Framework_TestCase
{
    public function testSearch()
    {
        $engine = new PhpReadEngine();

        $expected = [sprintf('%s/Fixtures/multi_bytes.txt', __DIR__), sprintf('%s/Fixtures/normal.txt', __DIR__)];
        $result = iterator_to_array($engine->search('normal', sprintf('%s/Fixtures', __DIR__)));

        $this->assertCount(2, $result);
        $this->assertCount(0, array_diff($expected, $result));
    }

    /**
     * @expectedException \Vilks\FileSearchBundle\Exception\IncorrectPathException
     */
    public function testSearchUnExists()
    {
        $engine = new PhpReadEngine();
        $engine->search('normal', sprintf('%s/Fixtures/1', __DIR__));
    }

    /**
     * @expectedException \Vilks\FileSearchBundle\Exception\IncorrectPathException
     */
    public function testSearchFilePath()
    {
        $engine = new PhpReadEngine();
        $engine->search('normal', sprintf('%s/Fixtures/normal.txt', __DIR__));
    }


    /**
     * @expectedException \Vilks\FileSearchBundle\Exception\EmptyNeedleException
     */
    public function testSearchEmptyNeedle()
    {
        $engine = new PhpReadEngine();
        $engine->search('', sprintf('%s/Fixtures', __DIR__));
    }
}
