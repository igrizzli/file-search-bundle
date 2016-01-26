<?php
namespace Vilks\FileSearchBundle\Engine;

use Vilks\FileSearchBundle\Exception\EmptyNeedleException;
use Vilks\FileSearchBundle\Exception\IncorrectPathException;

interface FileSearchEngineInterface
{
    /**
     * Search files which have needle in body.
     * Return list of their paths
     *
     * @param string $needle Search query
     * @param string $path Directory for searching in
     *
     * @return \Traversable|array
     *
     * @throws EmptyNeedleException
     * @throws IncorrectPathException
     */
    public function search($needle, $path);
}
