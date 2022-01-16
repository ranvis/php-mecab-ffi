<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab\Test;

/**
 * Simple trait to call private/protected method.
 * User classes cannot have __call() magic method.
 */
trait CallTrait
{
    public function __call(string $name, array $args)
    {
        if ($name[0] !== '_') {
            throw new \InvalidArgumentException("invalid method name: $name");
        }
        $name = substr($name, 1);
        return $this->$name(...$args);
    }
}
