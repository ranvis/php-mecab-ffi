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
    private static ?FFI $ffi = null;

    /**
     * Allocate a memory buffer.
     *
     * @param string $buf The buffer contents.
     * @param ?FFI $ffi A FFI instance.
     * @return FFI\CData The allocated buffer.
     */
    public static function newBuffer(string $buf, ?FFI $ffi = null): FFI\CData
    {
        $ffi ??= self::getFfi();
        $length = strlen($buf);
        $mem = $ffi->new('char [' . max(1, $length) . ']');
        FFI::memcpy($mem, $buf, $length);
        return $mem;
    }

    /**
     * Allocate a C-string buffer.
     *
     * @param string $str The string contents.
     * @param ?FFI $ffi A FFI instance.
     * @return FFI\CData The allocated string (NUL-terminated.)
     */
    public static function newCString(string $str, ?FFI $ffi = null): FFI\CData
    {
        if (str_contains($str, "\0")) {
            throw new \InvalidArgumentException('C-string should not contain NUL characters');
        }
        $ffi ??= self::getFfi();
        $length = strlen($str);
        $mem = $ffi->new('char [' . ($length + 1) . ']');
        FFI::memcpy($mem, $str, $length);
        $mem[$length] = "\0";
        return $mem;
    }

    /**
     * Create C-string pointer list from an array.
     * @param array $args List of arguments converted to C-string
     * @param ?FFI $ffi A FFI instance.
     * @return array The result as [0]. List of elements in [1], which need to be kept for the lifetime of [0].
     */
    public static function newArgs(array $args, ?FFI $ffi = null): array
    {
        $ffi ??= self::getFfi();
        $gc = [];
        $argsList = $ffi->new('char *[' . count($args) . ']');
        $index = 0;
        foreach ($args as $arg) {
            $argCharP = self::newCString((string)$arg, $ffi);
            $gc[] = $argCharP;
            $argsList[$index++] = $ffi->cast('char *', FFI::addr($argCharP));
        }
        return [$argsList, $gc];
    }

    protected static function getFfi(): FFI
    {
        return self::$ffi ??= FFI::cdef();
    }
}
