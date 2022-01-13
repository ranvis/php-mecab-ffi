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
    private const SCOPE_NAME = 'Ranvis/MeCab/1';

    private string $libPath;
    private FFI $lib;

    public function __construct(
        string|null|false $libPath = null,
    ) {
        if ($libPath !== false) {
            $libPath ??= 'libmecab.' . PHP_SHLIB_SUFFIX;
            $defs = Header::get();
            $this->lib = FFI::cdef($defs, $libPath);
        }
        $this->libPath = $libPath;
    }

    public static function fromScope(string $scope = self::SCOPE_NAME): static
    {
        $instance = new static(false);
        $instance->lib = FFI::scope($scope);
        return $instance;
    }

    public function lib(): FFI
    {
        return $this->lib;
    }

    public function getVersion(): string
    {
        return $this->lib->mecab_version();
    }

    public function getPreloader(string $scope = self::SCOPE_NAME): string
    {
        $scope = <<<"END"
            #define FFI_SCOPE "$scope"
            #define FFI_LIB "$this->libPath"
            END;
        $copyright = Header::getCopyright();
        $header = Header::get();
        return <<<"END"
            $scope

            $copyright

            $header
            END;
    }
}
