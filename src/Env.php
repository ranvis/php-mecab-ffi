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

    /**
     * Instantiate MeCab environment.
     *
     * Load and initialize MeCab shared library.
     *
     * @param string|null|false $libPath A path to the library.
     * Null if it can be found on the default location.
     * False is for internal use.
     */
    public function __construct(
        string|null|false $libPath = null,
    ) {
        if ($libPath !== false) {
            $libPath ??= 'libmecab.' . PHP_SHLIB_SUFFIX;
            $defs = Header::get();
            $this->lib = FFI::cdef($defs, $libPath);
            $this->libPath = $libPath;
        }
    }

    /**
     * Preload MeCab library.
     *
     * This method is expected to be called from opcache.preload script.
     *
     * @param string|null $libPath A path to the library.
     * Null if it can be found on the default location.
     * @param string|null $scope A scope name to preload to.
     * Null to load to the default scope.
     * @return static A created instance.
     */
    public static function preload(?string $libPath = null, ?string $scope = null): ?static
    {
        $instance = new static(false);
        $instance->libPath = $libPath ?? 'libmecab.' . PHP_SHLIB_SUFFIX;
        $preloader = $instance->getPreloader($scope);
        if (($tmpPath = tempnam(sys_get_temp_dir(), 'tmpMeCab')) === false) {
            throw new \RuntimeException("Unable to create a temporary file.");
        }
        try {
            if (!file_put_contents($tmpPath, $preloader)) {
                throw new \RuntimeException("Unable to write to the temporary file.");
            }
            $instance->lib = FFI::load($tmpPath);
        } finally {
            unlink($tmpPath);
        }
        return $instance;
    }

    /**
     * Instantiate MeCab environment from preloaded state.
     *
     * @param string|null $scope Null, or a non-default scope name set for preloading.
     * @return static A created instance.
     */
    public static function fromScope(?string $scope = null): static
    {
        $scope ??= self::SCOPE_NAME;
        $instance = new static(false);
        $instance->lib = FFI::scope($scope);
        return $instance;
    }

    /** @internal */
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

    /** @internal */
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
