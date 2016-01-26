<?php
namespace Vilks\FileSearchBundle\Engine\PhpRead;

/**
 * Iterator for map function
 *
 * @package Vilks\FileSearchBundle\Engine\PhpRead
 */
class CallbackIterator extends \IteratorIterator
{
    /** @var callable */
    private $callback;

    public function __construct(\Iterator $iterator, callable $callback)
    {
        parent::__construct($iterator);
        $this->callback = $callback;
    }

    public function current()
    {
        $callback = $this->callback;

        return $callback(parent::current());
    }
}
