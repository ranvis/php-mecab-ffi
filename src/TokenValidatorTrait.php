<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

trait TokenValidatorTrait
{
    /**
     * Make sure the token is valid. i.e. It has been allocated and not freed since.
     */
    protected function validateToken(): void
    {
        if (!$this->token->get()) {
            throw new \RuntimeException('Object is stale');
        }
    }
}
