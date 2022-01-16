<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Ranvis\MeCab\Token
 */
class TokenTest extends TestCase
{
    public function testWrap(): void
    {
        $token = new Token();
        $tokenRef = $token->wrap();
        $this->assertNotNull($tokenRef->get());
        $token = null;
        $this->assertNull($tokenRef->get());
    }

    public function testAddChild(): void
    {
        $token = new Token();
        $obj = new \stdClass();
        $ref = \WeakReference::create($obj);
        $token->addChild($obj);
        $this->assertNotNull($ref->get());
        $obj = null;
        $this->assertNotNull($ref->get());
        $token = null;
        $this->assertNull($ref->get());
    }
}
