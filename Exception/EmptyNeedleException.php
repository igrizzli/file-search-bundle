<?php
namespace Vilks\FileSearchBundle\Exception;

class EmptyNeedleException extends \InvalidArgumentException
{
    /**
     * @inheritDoc
     */
    public function __construct()
    {
        parent::__construct('Needle can\'t be empty');
    }
}
