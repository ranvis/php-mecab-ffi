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
        $mem = FFI::new('struct { char value[' . max(1, $length) . ']; }');
        FFI::memcpy($mem->value, $buf, $length);
        return $mem;
    }

    /**
     * Allocate a C-string buffer.
     *
     * @param string $str The string contents.
     * @return FFI\CData The allocated string (NUL-terminated, in struct { char value[]; }.)
     */
    public static function newCString(string $str): FFI\CData
    {
        if (str_contains($str, "\0")) {
            throw new \InvalidArgumentException('C-string should not contain NUL characters');
        }
        $length = strlen($str);
        $mem = FFI::new('struct { char value[' . ($length + 1) . ']; }');
        FFI::memcpy($mem->value, $str, $length);
        $mem->value[$length] = "\0";
        return $mem;
    }
}
