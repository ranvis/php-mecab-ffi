<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use FFI;

class Node extends NodeBase implements \IteratorAggregate
{
    public function __construct(
        FFI\CData|\stdClass $node,
        Token|\WeakReference $token,
        private NodeFactory $factory,
    ) {
        parent::__construct($node, $token);
    }

    public function getIterator(): NodeIterator
    {
        return new NodeIterator($this);
    }

    public function getWalker(): NodeWalkerIterator
    {
        return new NodeWalkerIterator($this->node, $this->token, $this->factory);
    }

    protected function traverseNode(string $name): ?self
    {
        $this->validateToken();
        if (!($ptr = $this->node->$name)) {
            return null;
        }
        return $this->factory->create($ptr, $this->token);
    }
}
