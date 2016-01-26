<?php
namespace Vilks\FileSearchBundle\Engine\PhpRead;

use Vilks\FileSearchBundle\Engine\FileSearchEngineInterface;
use Vilks\FileSearchBundle\Exception\EmptyNeedleException;
use Vilks\FileSearchBundle\Exception\IncorrectPathException;

class PhpReadEngine implements FileSearchEngineInterface
{
    /**
     * {@inheritDoc}
     */
    public function search($needle, $path)
    {
        $pathInfo = new \SplFileInfo($path);
        if (!$pathInfo->isDir()) {
            throw new IncorrectPathException('Path must be directory');
        }

        if (!mb_strlen($needle)) {
            throw new EmptyNeedleException;
        }

        $directoryIterator = new \RecursiveDirectoryIterator($path, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iterator = new \RecursiveIteratorIterator($directoryIterator);
        $filter = new FileContentFilterIterator($needle, $iterator);

        return new CallbackIterator(
            $filter,
            function (\SplFileInfo $file) {
                return $file->getPathname();
            }
        );
    }
}
