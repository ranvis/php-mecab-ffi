<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

namespace Ranvis\MeCab;

// flags
class RequestType
{
    public const ONE_BEST = 1;
    public const N_BEST = 2;
    public const PARTIAL = 4;
    public const MARGINAL_PROB = 8;
    public const ALTERNATIVE = 16;
    public const ALL_MORPHS = 32;
    public const ALLOCATE_SENTENCE = 64;
}
