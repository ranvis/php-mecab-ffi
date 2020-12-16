<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use FFI;

class Env
{
    private FFI $lib;

    public function __construct(
        string $libPath = 'libmecab.' . PHP_SHLIB_SUFFIX,
    ) {
        $defs = Header::get();
        $this->lib = FFI::cdef($defs, $libPath);
    }

    public function lib(): FFI
    {
        return $this->lib;
    }

    public function getVersion(): string
    {
        return $this->lib->mecab_version();
    }
}
