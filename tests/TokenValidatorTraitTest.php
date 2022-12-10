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
 * @covers \Ranvis\MeCab\TokenValidatorTrait
 */
class TokenValidatorTraitTest extends TestCase
{
    public function testValidateToken(): void
    {
        $impl = new class {
            use CallTrait;
            use TokenValidatorTrait;

            public $token;
        };
        $token = new Token();
        $tokenRef = \WeakReference::create($token);
        $impl->token = $tokenRef;
        $impl->_validateToken();
        $token = null;
        $this->expectException(\RuntimeException::class);
        $impl->_validateToken();
    }
}
