<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Ranvis\MeCab\NodeWalker
 */
class NodeWalkerTest extends TestCase
{
    public function testToNode(): void
    {
        $obj = (object)['next' => null, 'prev' => null, 'id' => 1];
        $obj2 = (object)['next' => null, 'prev' => $obj, 'id' => 2];
        $obj->next = $obj2;
        $token = new \stdClass();
        $factory = new NodeFactory();
        $instance = $factory->create($obj, \WeakReference::create($token));
        $nodesW = [];
        foreach ($instance->getWalker() as $node) {
            $nodesW[] = $node->toNode();
        }
        foreach ($instance as $node) {
            $nodes[] = $node;
        }
        $this->assertSame($nodesW, $nodes);
    }
}
