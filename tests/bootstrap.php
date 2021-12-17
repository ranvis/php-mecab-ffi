<?php
/**
 * @author SATO Kentaro
 * @license BSD-2-Clause
 */

declare(strict_types=1);

$loader = require(__DIR__ . '/../vendor/autoload.php');
$loader->addPsr4('Ranvis\MeCab\\', __DIR__ . '/../src');
