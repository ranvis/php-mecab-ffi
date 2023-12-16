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
     * @param Token|\WeakReference $token Token or its reference that node depends on.
     * @return Node Node instance.
     */
    public function create(FFI\CData|\stdClass $nodeP, Token|\WeakReference $token): Node
    {
        if (!($node = ($this->nodes[$nodeP->id] ?? null)?->get())) {
            $node = new Node($nodeP, $token, $this);
            $this->nodes[$nodeP->id] = \WeakReference::create($node);
        }
        return $node;
    }

    public function __debugInfo(): array
    {
        $props = get_mangled_object_vars($this);
        unset($props["\0" . __CLASS__ . "\0nodes"]);
        $props = [
            "\0" . __CLASS__ . "\0count(nodes)" => count($this->nodes),
        ];
        return $props;
    }
}
