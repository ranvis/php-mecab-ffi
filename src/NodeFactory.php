<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use FFI;

class NodeFactory
{
    private array $nodes = [];

    /**
     * Create a new node or return a node for the node object.
     *
     * @param FFI\CData|\stdClass $nodeP A node object that implements id property.
     * @param Token $token Token that node depends on.
     * @return Node Node instance.
     */
    public function create(FFI\CData|\stdClass $nodeP, Token $token): Node
    {
        if (!($node = ($this->nodes[$nodeP->id] ?? null)?->get())) {
            $node = new Node($nodeP, $token->wrap(), $this);
            $this->nodes[$nodeP->id] = \WeakReference::create($node);
        }
        return $node;
    }
}
