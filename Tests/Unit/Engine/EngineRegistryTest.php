<?php
namespace Vilks\FileSearchBundle\Tests\Unit\Engine;

use Vilks\FileSearchBundle\Engine\EngineRegistry;
use Vilks\FileSearchBundle\Engine\FileSearchEngineInterface;

class EngineRegistryTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $registry = new EngineRegistry();
        $mock1 = $this->getEngineMock();
        $registry->add('test', $mock1);
        $mock2 = $this->getEngineMock();
        $registry->add('test2', $mock2);

        $this->assertSame($mock1, $registry->get('test'));

        $registry->setDefault('test2');
        $this->assertSame($mock2, $registry->get());

        $result = $registry->names();
        $expects = ['test', 'test2'];

        $this->assertCount(2, $result);
        $this->assertCount(0, array_diff($expects, $result));
    }

    /**
     * @expectedException \Vilks\FileSearchBundle\Exception\EngineNotExistsException
     */
    public function testGetUndefined()
    {
        $registry = new EngineRegistry();
        $registry->add('test', $this->getEngineMock());
        $registry->add('test2', $this->getEngineMock());

        $registry->get('test3');
    }

    /**
     * @expectedException \Vilks\FileSearchBundle\Exception\EngineNotExistsException
     */
    public function testGetUndefinedDefault()
    {
        $registry = new EngineRegistry();
        $registry->add('test', $this->getEngineMock());
        $registry->add('test2', $this->getEngineMock());

        $registry->get();
    }

    /**
     * @return FileSearchEngineInterface
     */
    private function getEngineMock()
    {
        return $this->getMockForAbstractClass('\\Vilks\\FileSearchBundle\\Engine\\FileSearchEngineInterface');
    }
}
