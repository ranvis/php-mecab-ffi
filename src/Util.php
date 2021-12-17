<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

class Util
{
    public static function strGetCsv(string $str): ?array
    {
        // quoted if a field contains quotes/commas.
        // string cannot contain CRLF.
        // leave as is if an unquoted field contains quotes.
        if (!str_contains($str, '"')) {
            return explode(',', $str);
        }
        $list = [];
        for ($i = 0;;) {
            if (($str[$i] ?? '') === '"') { // quoted
                $i++;
                if (!preg_match('/\G(?>((?:[^"]+|"")*))"(,|\z)/', $str, $match, 0, $i)) {
                    return null; // illegal format
                }
                $list[] = str_replace('""', '"', $match[1]); // unescape quote
                if ($match[2] !== ',') { // end of string
                    break;
                }
                $i += strlen($match[0]);
            } elseif (($end = strpos($str, ',', $i)) !== false) {
                $list[] = substr($str, $i, $end - $i);
                $i = $end + 1; // skip comma
            } else {
                $list[] = substr($str, $i);
                break;
            }
        }
        return $list;
    }
}
