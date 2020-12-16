<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

namespace Ranvis\MeCab;

use FFI;

class DictionaryInfo
{
    private string $fileName;
    private string $charset;
    private int $size;
    private int $type;
    private int $leftAttrsSize;
    private int $rightAttrsSize;
    private int $version;

    public function __construct(FFI\CData $info)
    {
        $this->fileName = FFI::string($info->filename);
        $this->charset = FFI::string($info->charset);
        $this->size = $info->size;
        $this->type = $info->type;
        $this->leftAttrsSize = $info->lsize;
        $this->rightAttrsSize = $info->rsize;
        $this->version = $info->version;
    }

    public function getFileName(): string
    {
        return $this->fileName;
    }

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function getSize(): int
    {
        return $this->size;
    }

    public function getType(): int
    {
        return $this->type;
    }

    public function getLeftAttrsSize(): int
    {
        return $this->leftAttrsSize;
    }

    public function getRightAttrsSize(): int
    {
        return $this->rightAttrsSize;
    }

    public function getVersion(): int
    {
        return $this->version;
    }
}
