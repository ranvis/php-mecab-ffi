<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Ranvis\MeCab\Util
 */
class UtilTest extends TestCase
{
    public function testStrFromCsv(): void
    {
        $this->assertSame([], Util::fromCsv(''));
        $this->assertSame(['aa','bb','cc'], Util::fromCsv('aa,bb,cc'));
        $this->assertSame(['','bb','',''], Util::fromCsv(',bb,,'));
        $this->assertSame(['aa','bb'], Util::fromCsv('"aa",bb'));
        $this->assertSame(['aa','bb'], Util::fromCsv('aa,"bb"'));
        $this->assertSame([''], Util::fromCsv('""'));
        $this->assertSame(['aa',''], Util::fromCsv('"aa",'));
        $this->assertSame(['a"a'], Util::fromCsv('"a""a"'));
        $this->assertSame(['a""a'], Util::fromCsv('"a""""a"'));
        $this->assertSame([''], Util::fromCsv('""'));
        $this->assertSame(['"'], Util::fromCsv('""""'));
        $this->assertNull(Util::fromCsv('"a"a"'));
        $this->assertNull(Util::fromCsv('"a"""a"'));
        $this->assertNull(Util::fromCsv('"'));
        $this->assertNull(Util::fromCsv('"""'));
        $this->assertNull(Util::fromCsv('"aa"a'));
    }

    public function testToCsv(): void
    {
        $this->assertSame('', Util::toCsv([]));
        $this->assertSame('', Util::toCsv(['']));
        $this->assertSame('aa', Util::toCsv(['aa']));
        $this->assertSame('aa,bb,cc', Util::toCsv(['aa', 'bb', 'cc']));
        $this->assertSame('aa,,123', Util::toCsv(['aa', '', 123]));
        $this->assertSame(',', Util::toCsv(['', '']));
        $this->assertSame('"""aa""","bb,","c"",c"', Util::toCsv(['"aa"', 'bb,', 'c",c']));
    }
}
