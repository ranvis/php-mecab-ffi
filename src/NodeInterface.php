<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

namespace Ranvis\MeCab;

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
interface NodeInterface
{
    public function surface(): string;
    public function feature(): string;
    public function features(): array;
    public function id(): int;
    public function length(): int;
    public function rLength(): int;
    public function rcAttr(): int;
    public function lcAttr(): int;
    public function posId(): int;
    public function charType(): int;
    public function type(): int;
    public function isBest(): bool;
    public function alpha(): float;
    public function beta(): float;
    public function prob(): float;
    public function wCost(): int;
    public function cost(): int;

    public function __isset(string $name): bool;
    public function __get(string $name): string|int|float;
    public function __set(string $name, $value): void;
}
