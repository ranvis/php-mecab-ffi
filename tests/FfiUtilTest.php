<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Ranvis\MeCab\FfiUtil
 */
class FfiUtilTest extends TestCase
{
    public function testNewBuffer()
    {
        $buf = FfiUtil::newBuffer('buffer');
        $this->assertSame('b', $buf[0]);
        $this->assertSame('r', $buf[5]);
        $this->assertSame('buffer', \FFI::string($buf, 6));
        $this->expectException(\FFI\Exception::class);
        $this->assertNull($buf[6]);
    }

    public function testNewCString()
    {
        $buf = FfiUtil::newCString('string');
        $this->assertSame('s', $buf[0]);
        $this->assertSame('g', $buf[5]);
        $this->assertSame("\0", $buf[6]);
        $this->assertSame('string', \FFI::string($buf));
        $this->expectException(\InvalidArgumentException::class);
        $this->assertNull(FfiUtil::newCString("string\0"));
    }
}
