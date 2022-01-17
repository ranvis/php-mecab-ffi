<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use FFI;

class NodeWalkerIterator implements \Iterator
{
    private NodeWalker $head;
    private ?NodeWalker $current;

    public function __construct(
        \stdClass|FFI\CData $node,
        Token|\WeakReference $token,
        NodeFactory $factory,
    ) {
        $this->head = $this->current = new NodeWalker($node, $token, $factory);
    }

    public function current(): ?NodeWalker
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
