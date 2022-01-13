<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

trait TokenIssuerTrait
{
    private ?Token $validToken = null;

    /**
     * Renew token and free the old one.
     *
     * @return Token The new token.
     */
    private function changeToken(): Token
    {
        return $this->validToken = new Token();
    }

    /**
     * Free the current token.
     */
    private function freeToken(): void
    {
        $this->validToken = null;
    }

    /**
     * Get the current valid token.
     *
     * @return Token The current token.
     */
    protected function getValidToken(): Token
    {
        if (($token = $this->validToken) === null) {
            throw new \RuntimeException('Not available');
        }
        return $token;
    }
}
