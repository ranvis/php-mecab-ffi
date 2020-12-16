<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

trait TokenIssuerTrait
{
    private function changeToken(): Token
    {
        return $this->validToken = new Token();
    }
}
