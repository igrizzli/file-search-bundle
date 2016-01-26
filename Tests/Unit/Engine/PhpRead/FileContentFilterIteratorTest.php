<?php
namespace Vilks\FileSearchBundle\Tests\Unit\Engine\PhpRead;

use Vilks\FileSearchBundle\Engine\PhpRead\FileContentFilterIterator;

class FileContentFilterIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testAccept()
    {
        $data = [
            new \SplFileInfo(sprintf('%s/Fixtures', __DIR__)),
            new \SplFileInfo(sprintf('%s/Fixtures/image.jpg', __DIR__)),
            new \SplFileInfo(sprintf('%s/Fixtures/multi_bytes.txt', __DIR__)),
            new \SplFileInfo(sprintf('%s/Fixtures/normal.txt', __DIR__)),
            new \SplFileInfo(sprintf('%s/Fixtures/more.txt', __DIR__))
        ];

        $iterator = new FileContentFilterIterator('normal', new \ArrayIterator($data));

        $iterator->next();
        $this->assertEquals($data[2]->getPathname(), $iterator->current()->getPathname());
        $iterator->next();
        $this->assertEquals($data[3]->getPathname(), $iterator->current()->getPathname());
        $iterator->next();
        $this->assertNull($iterator->current());
    }


    /**
     * @expectedException \InvalidArgumentException
     */
    public function testWrongIterator()
    {
        $data = [sprintf('%s/Fixtures', __DIR__), 1, 2, 3, 4];

        $iterator = new FileContentFilterIterator('normal', new \ArrayIterator($data));

        $iterator->next();
    }
}
