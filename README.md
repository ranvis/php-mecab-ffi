# PHP-MeCab-FFI

MeCab binding using FFI.


## License

BSD 2-Clause License


## Installation

`
composer.phar require ranvis/mecab:^0.1
`

(On Windows cmd prompt, caret `^` must be either quoted or doubled.)

Make sure you have installed [MeCab](http://taku910.github.io/mecab/) 0.996 (or later compatible version) on your system along with the dictionary.
On some Linux distros, there should be a pre-built package.


## Example Usage

```php
use Ranvis\MeCab;

require_once(__DIR__ . '/vendor/autoload.php');

$env = new MeCab\Env(); // libmecab.{so,dll} should be in your PATH
//$env = new MeCab\Env('/usr/lib64/libmecab.so');
var_dump($env->getVersion());

$mecab = new MeCab\Tagger($env/*, ['--rcfile', '/path/to/mecabrc']*/);

$headNode = $mecab->parseToNode("こんにちは、世界！");
foreach ($headNode as $node) {
    echo $node->id() . " " . $node->surface() . "\n";
}
```
