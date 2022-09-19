<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use FFI;

/**
 * A proxy node that changes the node structure it points to on traverse,
 * thus the overhead is smaller than Node.
 */
class NodeWalker extends NodeBase
{
    public function __construct(
        \stdClass|FFI\CData $node,
        Token|\WeakReference $token,
        private NodeFactory $factory,
    )
    {
        parent::__construct($node, $token);
    }

    /**
     * Get stable Node instance for the node.
     *
     * @return Node A Node instance.
     */
    public function toNode(): Node
    {
        return $this->factory->create($this->node, $this->token);
    }

    protected function traverseNode(string $name): ?self
    {
        $this->validateToken();
        if (!($ptr = $this->node->$name)) {
            return null;
        }
        $this->node = $ptr;
        return $this;
    }
}
