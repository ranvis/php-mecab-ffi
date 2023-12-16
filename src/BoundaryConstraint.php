<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

// enum
class BoundaryConstraint
{
    public const ANY = 0;
    public const TOKEN_BOUNDARY = 1;
    public const INSIDE_TOKEN = 2;

    /** Use clearer name TOKEN_BOUNDARY instead */
    public const TOKEN = 1;
}
