<?php
namespace Vilks\FileSearchBundle\Exception;

class IncorrectPathException extends \InvalidArgumentException
{
    /**
     * @inheritDoc
     */
    public function __construct($message = null)
    {
        parent::__construct($message ?: 'Incorrect path');
    }
}
