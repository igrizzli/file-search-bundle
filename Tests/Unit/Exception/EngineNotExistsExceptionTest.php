<?php
namespace Vilks\FileSearchBundle\Tests\Unit\Exception;

use Vilks\FileSearchBundle\Exception\EngineNotExistsException;

class EngineNotExistsExceptionTest extends \PHPUnit_Framework_TestCase
{
    public function testGetName()
    {
        $e = new EngineNotExistsException('test');

        $this->assertEquals('test', $e->getName());
        $this->assertContains('test', $e->getMessage());
    }
}
