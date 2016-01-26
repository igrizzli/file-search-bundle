<?php
namespace Vilks\FileSearchBundle\Tests\Unit\Engine\PhpRead;

use Vilks\FileSearchBundle\Engine\PhpRead\MultiByteFileObject;

class MultiByteFileObjectTest extends \PHPUnit_Framework_TestCase
{
    public function testCountBuffer()
    {
        $this->assertEquals(
            5 * MultiByteFileObject::DEFAULT_BUFFER_MULTIPLIER,
            MultiByteFileObject::countBuffer('test1')
        );

        $this->assertEquals(
            5 * MultiByteFileObject::DEFAULT_BUFFER_MULTIPLIER,
            MultiByteFileObject::countBuffer('ЁH μဩ')
        );
    }

    public function testFread()
    {
        $file = new MultiByteFileObject(sprintf('%s/Fixtures/%s', __DIR__, 'normal.txt'));
        $i = 0;
        while (!$file->eof()) {
            $file->fread(1);
            $i++;
        }
        $this->assertEquals(64, $i);

        $file = new MultiByteFileObject(sprintf('%s/Fixtures/%s', __DIR__, 'multi_bytes.txt'));
        $i = 0;
        while (!$file->eof()) {
            $file->fread(1);
            $i++;
        }
        $this->assertEquals(57, $i);

        $file = new MultiByteFileObject(sprintf('%s/Fixtures/%s', __DIR__, 'multi_bytes.txt'));
        $i = 0;
        while (!$file->eof()) {
            $this->assertEquals($i==11 ? 1 : 5, mb_strlen($file->fread(5)));
            $i++;
        }
        $this->assertEquals(12, $i);

        $file = new MultiByteFileObject(sprintf('%s/Fixtures/%s', __DIR__, 'normal.txt'));
        $result = $file->fread(40);
        $this->assertEquals(40, mb_strlen($result));

        $file = new MultiByteFileObject(sprintf('%s/Fixtures/%s', __DIR__, 'multi_bytes.txt'));
        $result = $file->fread(40);
        $this->assertEquals(40, mb_strlen($result));
    }


    public function testContains()
    {
        $file = new MultiByteFileObject(sprintf('%s/Fixtures/%s', __DIR__, 'normal.txt'));
        $this->assertTrue($file->contains('Hello'));
        $this->assertTrue($file->contains('Hello', 6));
        $this->assertTrue($file->contains('lo wo', 6));
        $this->assertTrue($file->contains('lo wo'));
        $this->assertFalse($file->contains('bad', 6));

        $file = new MultiByteFileObject(sprintf('%s/Fixtures/%s', __DIR__, 'multi_bytes.txt'));
        $this->assertTrue($file->contains('ЁЖИГ'));
        $this->assertTrue($file->contains('ЁЖИГ', 4));
        $this->assertTrue($file->contains('т мн', 4));
        $this->assertFalse($file->contains('▧'));
    }
}
