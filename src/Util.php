<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

final class Util
{
    /**
     * Parse MeCab CSV line into an array.
     *
     * @param string $str A MeCab flavored CSV string.
     * @return array|null CSV items array. Null if CSV is broken.
     */
    public static function fromCsv(string $str): ?array
    {
        // An empty string becomes an empty array.
        // Quoted if a field contains quotes/commas.
        // String cannot contain CRLF.
        // Leave it as is if an unquoted field contains quotes.
        if (!str_contains($str, '"')) {
            return $str === '' ? [] : explode(',', $str);
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

    /**
     * Build array into MeCab CSV string.
     *
     * The CSV string can be fed into arguments that accepts multiple values.
     *
     * @param array $items The values to convert to CSV.
     * @return string A MeCab CSV string.
     */
    public static function toCsv(array $items): string
    {
        return implode(',', array_map(fn ($v) => self::toCsvItem((string)$v), $items));
    }

    protected static function toCsvItem(string $value): string
    {
        // Do not skip leading /[\t ]/ for this method.
        if (!preg_match('/[",]/', $value)) {  // faster than strcspn() (aside from the initial regex compilation)
            return $value;
        }
        return '"' . str_replace('"', '""', $value) . '"';
    }
}
