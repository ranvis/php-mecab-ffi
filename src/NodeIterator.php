<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

class NodeIterator implements \Iterator
{
    private ?Node $current;

    public function __construct(
        private Node $head
    ) {
        $this->current = $head;
    }

    public function current(): ?Node
    {
        return $this->current;
    }

    public function key(): int
    {
        return $this->current->id();
    }

    public function next(): void
    {
        $this->current = $this->current->next();
    }

    public function rewind(): void
    {
        $this->current = $this->head;
    }

    public function valid(): bool
    {
        return $this->current !== null;
    }
}
