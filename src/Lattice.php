<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

use FFI;

class Lattice
{
    use TokenIssuerTrait;

    private FFI\CData $lattice;
    private array $gc = [];
    private NodeFactory $factory;

    public function __construct(
        private Env $env,
    ) {
        $this->lattice = $this->env->lib()->mecab_lattice_new();
    }

    public function __destruct()
    {
        $this->freeToken();
        $this->env->lib()->mecab_lattice_destroy($this->lattice);
    }

    public function getObject(): FFI\CData
    {
        return $this->lattice;
    }

    public function clear(): void
    {
        $this->env->lib()->mecab_lattice_clear($this->lattice);
    }

    public function isAvailable(): bool
    {
        return (bool)$this->env->lib()->mecab_lattice_is_available($this->lattice);
    }

    public function getBosNode(): ?Node
    {
        $token = $this->getValidToken();
        $nodeP = $this->env->lib()->mecab_lattice_get_bos_node($this->lattice);
        return $this->factory->create($nodeP, $token);
    }

    public function getEosNode(): ?Node
    {
        $token = $this->getValidToken();
        $nodeP = $this->env->lib()->mecab_lattice_get_eos_node($this->lattice);
        return $this->factory->create($nodeP, $token);
    }

    public function getBeginNodes(): array
    {
        $nodePp = $this->env->lib()->mecab_lattice_get_all_begin_nodes($this->lattice);
        return $this->convertNodePp($nodePp);
    }

    public function getEndNodes(): array
    {
        $nodePp = $this->env->lib()->mecab_lattice_get_all_end_nodes($this->lattice);
        return $this->convertNodePp($nodePp);
    }

    protected function convertNodePp(FFI\CData $nodePp): array
    {
        $token = $this->getValidToken();
        $nodes = [];
        for ($i = 0; ($nodeP = $nodePp[$i]); $i++) {
            $nodes[] = $this->factory->create($nodeP, $token);
        }
        return $nodes;
    }

    public function getSentence(): ?string
    {
        $lib = $this->env->lib();
        $charP = $lib->mecab_lattice_get_sentence($this->lattice);
        $size = $lib->mecab_lattice_get_size($this->lattice);
        return $charP === null ? null : FFI::string($charP, $size);
    }

    public function setSentence(string $str): void
    {
        $this->changeToken();
        $this->factory = new NodeFactory();
        $this->gc["\0sentence"] = $buf = FfiUtil::newBuffer($str);
        $this->env->lib()->mecab_lattice_set_sentence2($this->lattice, $buf, strlen($str));
    }

    /** normalization factor of CRF */
    public function getZ(): float
    {
        return $this->env->lib()->mecab_lattice_get_z($this->lattice);
    }

    public function setZ(float $value): void
    {
        $this->env->lib()->mecab_lattice_set_z($this->lattice, $value);
    }

    /** temperature parameter theta */
    public function getTheta(): float
    {
        return $this->env->lib()->mecab_lattice_get_theta($this->lattice);
    }

    public function setTheta(float $value): void
    {
        $this->env->lib()->mecab_lattice_set_theta($this->lattice, $value);
    }

    public function next(): bool
    {
        return (bool)$this->env->lib()->mecab_lattice_next($this->lattice);
    }

    public function getRequestType(): int
    {
        return $this->env->lib()->mecab_lattice_get_request_type($this->lattice);
    }

    public function hasRequestType(int $requestType): bool
    {
        return (bool)$this->env->lib()->mecab_lattice_has_request_type($this->lattice, $requestType);
    }

    public function setRequestType(int $requestType): void
    {
        $this->env->lib()->mecab_lattice_set_request_type($this->lattice, $requestType);
    }

    public function addRequestType(int $requestType): void
    {
        $this->env->lib()->mecab_lattice_add_request_type($this->lattice, $requestType);
    }

    public function removeRequestType(int $requestType): void
    {
        $this->env->lib()->mecab_lattice_remove_request_type($this->lattice, $requestType);
    }

    public function newNode(): Node
    {
        $node = $this->env->lib()->mecab_lattice_new_node($this->lattice); // owned by lattice
        // XXX: Create new factory but the Node is not bound to it.
        // What is the use case for newNode()?
        return new Node($node, $this->getValidToken()->wrap(), new NodeFactory());
    }

    public function __toString(): string
    {
        return $this->env->lib()->mecab_lattice_tostr($this->lattice);
    }

    public function getNBestString(int $count): string
    {
        return $this->env->lib()->mecab_lattice_nbest_tostr($this->lattice, $count);
    }

    public function hasConstraint(): bool
    {
        return (bool)$this->env->lib()->mecab_lattice_has_constraint($this->lattice);
    }

    public function getBoundaryConstraint(int $position): int
    {
        return $this->env->lib()->mecab_lattice_get_boundary_constraint($this->lattice, $position);
    }

    public function setBoundaryConstraint(int $position, int $boundaryConstraint): void
    {
        $this->env->lib()->mecab_lattice_set_boundary_constraint($this->lattice, $position, $boundaryConstraint);
    }

    public function getFeatureConstraint(int $position): ?string
    {
        return $this->env->lib()->mecab_lattice_get_feature_constraint($this->lattice, $position);
    }

    public function setFeatureConstraint(int $start, int $end, string $feature): void
    {
        if (!isset($gc[$feature])) {
            $gc[$feature] = FfiUtil::newCString($feature);
        }
        $this->env->lib()->mecab_lattice_set_feature_constraint($this->lattice, $start, $end, $gc[$feature]);
    }

    public function getLastError(): string
    {
        return $this->env->lib()->mecab_lattice_strerror($this->lattice);
    }
}
