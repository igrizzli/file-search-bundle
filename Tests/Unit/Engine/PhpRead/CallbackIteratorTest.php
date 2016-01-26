<?php
namespace Vilks\FileSearchBundle\Tests\Unit\Engine\PhpRead;

use Vilks\FileSearchBundle\Engine\PhpRead\CallbackIterator;

class CallbackIteratorTest extends \PHPUnit_Framework_TestCase
{
    public function testCall()
    {
        $iterator = new CallbackIterator(new \ArrayIterator([1, 2, 3]), function ($num) {
            return $num + 10;

        });

        $result = [11, 12, 13];
        foreach ($iterator as $i => $item) {
            $this->assertEquals($result[$i], $item);
        }
    }
}
