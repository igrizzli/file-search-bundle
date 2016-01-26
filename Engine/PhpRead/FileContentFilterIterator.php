<?php
namespace Vilks\FileSearchBundle\Engine\PhpRead;

/**
 * Iterator for file filtering by word
 *
 * @package Vilks\FileSearchBundle\Engine\PhpRead
 */
class FileContentFilterIterator extends \FilterIterator
{
    private static $finfoInstance;

    private $needle;
    private $buffer;

    /**
     * @param string $needle
     * @param \Iterator $iterator
     */
    public function __construct($needle, \Iterator $iterator)
    {
        $this->needle = $needle;
        $this->buffer = MultiByteFileObject::countBuffer($needle);

        parent::__construct($iterator);
    }

    /**
     * @inheritDoc
     */
    public function accept()
    {
        /** @var \SplFileInfo $fileInfo */
        $fileInfo = $this->current();
        if (!$fileInfo instanceof \SplFileInfo) {
            throw new \InvalidArgumentException('Can filter only \\SplFileInfo iterators');
        }
        if ($this->isAccessible($fileInfo)) {
            $fileInfo->setFileClass('\\Vilks\\FileSearchBundle\\Engine\\PhpRead\\MultiByteFileObject');
            /** @var MultiByteFileObject $file */
            $file = $fileInfo->openFile('r');

            return $file->contains($this->needle, $this->buffer);
        }

        return false;
    }

    /**
     * @param \SplFileInfo $fileInfo
     *
     * @return bool
     */
    private static function isAccessible(\SplFileInfo $fileInfo)
    {
        return $fileInfo->isFile() &&
            $fileInfo->isReadable() &&
            self::finfoInstance()->file($fileInfo->getPathname()) !== 'binary';
    }

    /**
     * @return \finfo
     */
    private static function finfoInstance()
    {
        if (!self::$finfoInstance) {
            self::$finfoInstance = new \finfo(FILEINFO_MIME_ENCODING);
        }

        return self::$finfoInstance;
    }
}
