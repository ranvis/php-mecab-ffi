<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

// enum
class NodeType
{
    public const NORMAL = 0;
    public const UNKNOWN = 1;
    public const BOS = 2;
    public const EOS = 3;
    public const EON = 4;
}
