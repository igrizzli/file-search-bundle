<?php
namespace Vilks\FileSearchBundle\Engine\PhpRead;

/**
 * Extension for SplFileObject which allow to read data from multi byte files
 *
 * @package Vilks\FileSearchBundle\Engine\PhpRead
 */
class MultiByteFileObject extends \SplFileObject
{
    const DEFAULT_BUFFER_MULTIPLIER = 1024;

    /**
     * @param string $word
     *
     * @return int
     */
    public static function countBuffer($word)
    {
        return mb_strlen($word) * self::DEFAULT_BUFFER_MULTIPLIER;
    }

    /**
     * Check that file contain $needle string
     *
     * @param string $needle
     * @param int $buffer
     *
     * @return bool
     */
    public function contains($needle, $buffer = null)
    {
        $buffer = (int)$buffer;
        if (!$buffer) {
            $buffer = self::countBuffer($needle);
        }

        $prevHaystack = '';
        $this->rewind();
        while (!$this->eof()) {
            $haystack = $this->fread($buffer);
            if (mb_strpos($prevHaystack.$haystack, $needle) === false) {
                $prevHaystack = $haystack;
            } else {
                return true;
            }
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function fread($length)
    {
        $buffer = parent::fread($length);
        if ($this->eof()) {
            return $buffer;
        }

        $byte = ord(substr($buffer, -1, 1));
        if ($byte <= 0x7F) {
            return $this->fixLength($buffer, $length);
        }

        $count = 1;
        while ($byte < 0xC0 && $count < $length) {
            $count++;
            $byte = ord(substr($buffer, -$count, 1));
        }

        $mask = 0x40;
        $bitsCount = 1;
        while ($byte & $mask) {
            $mask = $mask >> 1;
            $bitsCount++;
        }
        $bytesToEnd = $bitsCount - $count;

        return $this->fixLength($buffer . ($bytesToEnd ? parent::fread($bytesToEnd) : ''), $length);
    }

    /**
     * @param string $result
     * @param int $length
     * @return string
     */
    private function fixLength($result, $length)
    {
        $size = mb_strlen($result);

        return  ($size >= $length) ? $result : ($result . $this->fread($length - $size));
    }
}
