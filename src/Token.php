<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

class Token
{
    private array $gc = [];

    public function wrap(): \WeakReference
    {
        return \WeakReference::create($this);
    }

    public function addChild(array|object $data): void
    {
        $this->gc[] = $data;
    }
}
