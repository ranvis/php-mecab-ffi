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
    private \WeakReference $wrapped;

    /**
     * Wrap the instance in a weak reference.
     *
     * @return \WeakReference
     */
    public function wrap(): \WeakReference
    {
        return $this->wrapped ??= \WeakReference::create($this);
    }

    /**
     * Bind data or array of data to the instance.
     *
     * When the instance is freed and no hard reference for the data is held, the data is freed too.
     *
     * @param object[]|object $data
     * @return void
     */
    public function addChild(array|object $data): void
    {
        $this->gc[] = $data;
    }
}
