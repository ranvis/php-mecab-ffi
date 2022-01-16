<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use PHPUnit\Framework\TestCase;
use Ranvis\MeCab\Test\CallTrait;

/**
 * @covers \Ranvis\MeCab\TokenIssuerTrait
 */
class TokenIssuerTraitTest extends TestCase
{
    private function getInstance(): object
    {
        return new class {
            use CallTrait;
            use TokenIssuerTrait;
        };
    }

    public function testInitialToken(): void
    {
        $impl = $this->getInstance();
        $this->expectException(\RuntimeException::class);
        $impl->_getValidToken();
    }

    public function testChangeToken(): void
    {
        $impl = $this->getInstance();
        $impl->_changeToken();
        $this->assertNotNull($impl->_getValidToken());
    }

    public function testFreeToken(): void
    {
        $impl = $this->getInstance();
        $impl->_changeToken();
        $impl->_freeToken();
        $this->expectException(\RuntimeException::class);
        $impl->_getValidToken();
    }
}
