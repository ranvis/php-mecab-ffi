# PHP-MeCab-FFI

MeCab binding using FFI.


## License

BSD 2-Clause License


## Installation

`
composer.phar require ranvis/mecab:^0.2
`

(On Windows `cmd` prompt, caret `^` must be either quoted or doubled.)

Make sure you have installed [MeCab](http://taku910.github.io/mecab/) 0.996 (or later compatible version) on your system along with the dictionary.
On some Linux distros, there should be a pre-built package.


## Example Usage

```php
use Ranvis\MeCab;

require_once(__DIR__ . '/vendor/autoload.php');

$env = new MeCab\Env(); // libmecab.{so,dll} should be in your PATH
//$env = new MeCab\Env('libmecab.so.2'); // libmecab.so.2 should be in your PATH
//$env = new MeCab\Env('/usr/lib64/libmecab.so.2'); // or specify explicitly
var_dump($env->getVersion());

$mecab = new MeCab\Tagger($env/*, ['--rcfile', '/path/to/mecabrc']*/);

$headNode = $mecab->parseToNode("こんにちは、世界！");
foreach ($headNode as $node) {
    echo $node->id() . " " . $node->surface() . "\n";
}
```

## Preloading Dynamic Library

Loading dynamic library using script via FFI has a small overhead compared with native extension.
This can be mitigated by using FFI's preload feature.
With preloading, daemon-like SAPI such as FPM can preprocess initialization of the library and reuse it afterwards.

To make use of this, we need to generate a header file for MeCab once.
Run bundled `gen_mecab_preloader` command with the destination (and MeCab library name/path.)

```sh
$ mkdir ffi_preload.d
$ vendor/bin/gen_mecab_preloader ffi_preload.d/mecab.h /path/to/libmecab.so
Generated: ffi_preload.d/mecab.h
```

Then set `ffi.preload` ini value to point to the file.
(The header file may have to be regenerated when this library is largely updated.)

Now to see if it works, we use CLI to run the following script.
Notice that `MeCab\Env` is now instantiated with `MeCab\Env::fromScope()` static method instead of `new` operator, to take advantage of preloading.

```sh
$ cat <<'END' > preload_test.php
<?php

require_once(__DIR__ . '/vendor/autoload.php');

use Ranvis\MeCab;

$env = MeCab\Env::fromScope();
var_dump($env->getVersion());
END
$ php -d ffi.preload=ffi_preload.d/*.h preload_test.php
string(5) "0.996"
```
