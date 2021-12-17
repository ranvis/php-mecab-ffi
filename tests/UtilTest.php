<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use PHPUnit\Framework\TestCase;

class UtilTest extends TestCase
{
    public function testStrGetCsv(): void
    {
        $this->assertSame([''], Util::strGetCsv(''));
        $this->assertSame(['aa','bb','cc'], Util::strGetCsv('aa,bb,cc'));
        $this->assertSame(['','bb','',''], Util::strGetCsv(',bb,,'));
        $this->assertSame(['aa','bb'], Util::strGetCsv('"aa",bb'));
        $this->assertSame(['aa','bb'], Util::strGetCsv('aa,"bb"'));
        $this->assertSame([''], Util::strGetCsv('""'));
        $this->assertSame(['aa',''], Util::strGetCsv('"aa",'));
        $this->assertSame(['a"a'], Util::strGetCsv('"a""a"'));
        $this->assertSame(['a""a'], Util::strGetCsv('"a""""a"'));
        $this->assertSame([''], Util::strGetCsv('""'));
        $this->assertSame(['"'], Util::strGetCsv('""""'));
        $this->assertNull(Util::strGetCsv('"a"a"'));
        $this->assertNull(Util::strGetCsv('"a"""a"'));
        $this->assertNull(Util::strGetCsv('"'));
        $this->assertNull(Util::strGetCsv('"""'));
        $this->assertNull(Util::strGetCsv('"aa"a'));
    }
}
