<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use FFI;

class FfiUtil
{
    /**
     * Allocate a memory buffer.
     *
     * @param string $buf The buffer contents.
     * @return FFI\CData The allocated buffer.
     */
    public static function newBuffer(string $buf): FFI\CData
    {
        $length = strlen($buf);
        $mem = FFI::new('char [' . max(1, $length) . ']');
        FFI::memcpy($mem, $buf, $length);
        return $mem;
    }

    /**
     * Allocate a C-string buffer.
     *
     * @param string $str The string contents.
     * @return FFI\CData The allocated string (NUL-terminated.)
     */
    public static function newCString(string $str): FFI\CData
    {
        if (str_contains($str, "\0")) {
            throw new \InvalidArgumentException('C-string should not contain NUL characters');
        }
        $length = strlen($str);
        $mem = FFI::new('char [' . ($length + 1) . ']');
        FFI::memcpy($mem, $str, $length);
        $mem[$length] = "\0";
        return $mem;
    }

    /**
     * Create C-string pointer list from an array.
     * @param array $args List of arguments converted to C-string
     * @return array The result as [0]. List of elements in [1], which need to be kept for the lifetime of [0].
     */
    public static function newArgs(array $args): array
    {
        $gc = [];
        $argsList = FFI::new('char *[' . count($args) . ']');
        $index = 0;
        foreach ($args as $arg) {
            $argCharP = self::newCString((string)$arg);
            $gc[] = $argCharP;
            $argsList[$index++] = FFI::cast('char *', FFI::addr($argCharP));
        }
        return [$argsList, $gc];
    }
}
