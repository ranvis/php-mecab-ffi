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

    public static function fromScope(?string $scope = null): static
    {
        $scope ??= self::SCOPE_NAME;
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

    public function tagger(array|string $args = []): Tagger
    {
        return new Tagger($this, $args);
    }

    public function model(array|string $args = []): Model
    {
        return new Model($this, $args);
    }

    public function lattice(): Lattice
    {
        return new Lattice($this);
    }

    public function getPreloader(?string $scope = null): string
    {
        $scope ??= self::SCOPE_NAME;
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
