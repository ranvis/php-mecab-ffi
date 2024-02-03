# PHP-MeCab-FFI

MeCab binding using FFI.


- [Changelog](CHANGELOG.md)

## License

BSD 2-Clause License

This library contains a part of MeCab interface definitions, which is licensed under BSD 3-Clause License as stated there.

## Installation

First make sure you have enabled FFI extension, which is bundled with PHP. Then execute the Composer command:

`
composer require "ranvis/mecab:^0.3"
`

Make sure you have installed [MeCab](http://taku910.github.io/mecab/) 0.996 (or later compatible version) on your system along with the dictionary.
On Windows and some Linux distros, there should be a pre-built package.

Last but not least, if you are going to use the library from non-CLI environment such as web server, `ffi.enable=true` instead of the restricted default `ffi.enable=preload` must be set in the system INI configuration used by the SAPI.

## Example Usage

```php
use Ranvis\MeCab;

require_once __DIR__ . '/vendor/autoload.php';

$mecab = new MeCab\Env(); // libmecab.{so,dll} should be in the PATH directory
//$mecab = new MeCab\Env('libmecab.so.2'); // or libmecab.so.2 in it
//$mecab = new MeCab\Env('/usr/lib64/libmecab.so.2'); // or specify explicitly
var_dump($mecab->getVersion());

$tagger = $mecab->tagger();
//$tagger = $mecab->tagger(['--rcfile', '/path/to/mecabrc']);

foreach ($tagger->getDictionaryInfo() as $info) {
    $name = $info->getFileName();
    $name = substr($name, strlen(dirname($name, 2)));
    printf("Dictionary: %s, Version: %d, Encoding: %s\n", $name, $info->getVersion(), $info->getCharset());
}

$headNode = $tagger->parseToNode("メカブはおやつに入りますか？");
foreach ($headNode as $node) {
    echo $node->surface() . ": " . $node->feature() . "\n";
}
```

## Preloading Dynamic Library

Loading dynamic library using script via FFI extension has a small overhead compared with native PHP extension.
This can be mitigated by using FFI's preload feature.
With preloading, daemon-like SAPI such as FPM can preprocess initialization of the library and reuse it afterwards.

### Preload using `ffi.preload`

To make use of this, we need to generate a header file for MeCab once.
Run bundled `gen_mecab_preloader` command with the destination (and MeCab library name/path).

```sh
$ mkdir ffi_preload.d
$ vendor/bin/gen_mecab_preloader ffi_preload.d/mecab.h /path/to/libmecab.so
Generated: ffi_preload.d/mecab.h
```

Then set `ffi.preload` ini value to point to the file.
(The header file may have to be regenerated in case this library is largely updated.)

Now to see if it works, we use CLI to run the following script.
Notice that `MeCab\Env` is now instantiated with the `MeCab\Env::fromScope()` static method instead of a `new` operator, to take advantage of preloading.

```sh
$ cat <<'END' > preload_test.php
<?php

require_once __DIR__ . '/vendor/autoload.php';

use Ranvis\MeCab;

$mecab = MeCab\Env::fromScope();
var_dump($mecab->getVersion());
END
$ php -d ffi.preload=ffi_preload.d/*.h preload_test.php
string(5) "0.996"
```

If your PHP is running in daemon-like style like FPM, restart the daemon process to take effect.

### Preload using `opcache.preload`

Note: OPcache's preloading is not supported in Windows. ([gh#4999](https://github.com/php/php-src/pull/4999))

Another way of preloading is to use OPcache's one.
OPcache also has a feature to preload classes that you use often.
FFI can be initialized during this step as well, provided that `opcache.preload_user` is not set, or set as the current system user
on PHP 8.3 and later. (Usually it is not set.)

In the PHP script specified in `opcache.preload` ini value, call `MeCab\Env::preload()` as follows:

```php
<?php // preloader.php

require_once __DIR__ . '/vendor/autoload.php';

\Ranvis\MeCab\Env::preload('/path/to/libmecab.so');
```

And then on the actual script, call `MeCab\Env::fromScope()` to instantiate like the former example.

```sh
$ php -d opcache.preload=preloader.php preload_test.php
```

While this looks simpler than the former way, a header file will be silently created on the system's temporary directory everytime OPcache's preloading triggers; since FFI doesn't allow in-memory interface definitions for preloading as of PHP 8.3.
