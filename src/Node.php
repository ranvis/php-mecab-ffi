<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use FFI;

/**
 * @property string $surface
 * @property string $feature
 * @property int $id
 * @property int $length
 * @property int $rlength
 * @property int $rcAttr
 * @property int $lcAttr
 * @property int $posid
 * @property int $char_type
 * @property int $stat
 * @property int $isbest
 * @property float $alpha
 * @property float $beta
 * @property int $prob
 * @property int $wcost
 * @property int $cost
 */
class Node implements \IteratorAggregate
{
    use TokenValidatorTrait;

    private const RAW_PROPS = ['surface', 'feature', 'id', 'length', 'rlength', 'rcAttr', 'lcAttr', 'posid', 'char_type', 'stat', 'isbest', 'alpha', 'beta', 'prob', 'wcost', 'cost'];

    public function __construct(
        private FFI\CData $node,
        private \WeakReference $token,
    ) {
    }

    public function getIterator(): NodeIterator
    {
        return new NodeIterator($this);
    }

    protected function traverseNode(string $name): ?self
    {
        // XXX: every time the node is traversed, a new different instance is created
        $this->validateToken();
        $ptr = $this->node->$name;
        return $ptr ? new self($ptr, $this->token) : null;
    }

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

    protected function getProperty(string $name): mixed
    {
        $this->validateToken();
        return $this->node->$name;
    }

    public function id(): int
    {
        return $this->getProperty('id');
    }

    public function length(): int
    {
        return $this->getProperty('length');
    }

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
    public function prob(): int
    {
        return $this->getProperty('prob');
    }

    /** word occurrence cost */
    public function wordCost(): int
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
        return in_array($name, self::RAW_PROPS, true);
    }

    public function __get(string $name): string|int|float
    {
        if (!in_array($name, self::RAW_PROPS, true)) {
            throw new \InvalidArgumentException('No such property');
        }
        if (!in_array($name, ['surface', 'feature'], true)) {
            return $this->$name();
        }
        return $this->getProperty($name);
    }

    public function __set(string $name, $value): void
    {
        throw new \RuntimeException('Property is read-only');
    }
}
