<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use FFI;

abstract class NodeBase implements NodeInterface
{
    use TokenValidatorTrait;

    protected \WeakReference $token;

    public function __construct(
        protected FFI\CData|\stdClass $node,
        Token|\WeakReference $token,
    ) {
        // The node can be a plain object, since mecab_node_t is never passed to the library.
        $this->token = ($token instanceof Token) ? $token->wrap() : $token;
    }

    abstract protected function traverseNode(string $name): ?self;

    public function next(): ?self
    {
        return $this->traverseNode('next');
    }

    public function prev(): ?self
    {
        return $this->traverseNode('prev');
    }

    public function nextEnd(): ?self
    {
        return $this->traverseNode('enext');
    }

    public function nextStart(): ?self
    {
        return $this->traverseNode('bnext');
    }

// struct mecab_path_t *rpath;
// struct mecab_path_t *lpath;

    public function surface(): string
    {
        $this->validateToken();
        return FFI::string($this->node->surface, $this->node->length);
    }

    public function feature(): string
    {
        $this->validateToken();
        return FFI::string($this->node->feature);
    }

    public function features(): array
    {
        return Util::fromCsv($this->feature()) ?? [];
    }

    protected function getProperty(string $name): mixed
    {
        $this->validateToken();
        return $this->node->$name;
    }

    /** node identifier */
    public function id(): int
    {
        return $this->getProperty('id');
    }

    /** length of the surface form */
    public function length(): int
    {
        return $this->getProperty('length');
    }

    /** length of the surface form including white space before the morph */
    public function rLength(): int
    {
        return $this->getProperty('rlength');
    }

    /** right context attribute */
    public function rcAttr(): int
    {
        return $this->getProperty('rcAttr');
    }

    /** left context attribute */
    public function lcAttr(): int
    {
        return $this->getProperty('lcAttr');
    }

    /** part-of-speech ID */
    public function posId(): int
    {
        return $this->getProperty('posid');
    }

    /** character type */
    public function charType(): int
    {
        return $this->getProperty('char_type');
    }

    /** morpheme type */
    public function type(): int
    {
        return $this->getProperty('stat');
    }

    /** is best */
    public function isBest(): bool
    {
        return (bool)$this->getProperty('isbest');
    }

    /** forward log probability */
    public function alpha(): float
    {
        return $this->getProperty('alpha');
    }

    /** backward log probability */
    public function beta(): float
    {
        return $this->getProperty('beta');
    }

    /** marginal probability */
    public function prob(): float
    {
        return $this->getProperty('prob');
    }

    /** word occurrence cost */
    public function wCost(): int
    {
        return $this->getProperty('wcost');
    }

    /** cumulative cost */
    public function cost(): int
    {
        return $this->getProperty('cost');
    }

    public function __isset(string $name): bool
    {
        return in_array($name, [
            'alpha', 'beta', 'char_type', 'cost', 'feature', 'id', 'isbest', 'lcAttr', 'length', 'posid', 'prob', 'rcAttr', 'rlength', 'stat', 'surface', 'wcost',
        ], true);
    }

    public function __get(string $name): string|int|float
    {
        assert($this->__isset($name));
        if (in_array($name, ['feature', 'surface'], true)) {
            return $this->$name();
        }
        return $this->getProperty($name);
    }

    public function __set(string $name, $value): void
    {
        throw new \RuntimeException('Property is read-only');
    }
}
