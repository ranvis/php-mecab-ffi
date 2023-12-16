# Changelog

[Unreleased]: https://github.com/ranvis/php-mecab-ffi/compare/v0.3.2...HEAD
## [Unreleased]
### Added
### Changed
### Removed
### Fixed
### Security
### Deprecated

[0.3.2]: https://github.com/ranvis/php-mecab-ffi/compare/v0.3.1..v0.3.2
## [0.3.2] - 2023-11-24
### Added
- Add `Lattice` instantiation with `Model`.
### Fixed
- Fix PHP 8.3 deprecation notices regarding `FFI`. (php-src@4acf008)
- Fix `Tagger` instantiation with `Model`.

[0.3.1]: https://github.com/ranvis/php-mecab-ffi/compare/v0.3.0..v0.3.1
## [0.3.1] - 2022-12-10
### Fixed
- Fix `NodeWalker->toNode()` returning a new node for every call.

[0.3.0]: https://github.com/ranvis/php-mecab-ffi/compare/v0.2.2..v0.3.0
## [0.3.0] - 2022-03-19
### Added
- Add `Env->tagger()`, `Env->model()`, `Env->lattice()`.
- Add class `NodeWalker` returned by `Node->getWalker()`, which moves internal pointer when iterating, instead of instantiating a new node as `Node` do.
- Add `Util::fromCsv()` and `::toCSV()`, which converts MeCab flavored CSV.
- Support OPcache preloading.
- Add defaults to preloader generator's interactive prompt.
- Internal optimization.
### Changed
- `Tagger->parseToNode()` tries to return the same node for the same underlying data.
- `NodeIterator` uses underlying ID as its key instead of index.
- The class `Util` is declared as `final`.
- Revert `Tagger` constructor default from `'--'` to `[]`.
  As the underlying call `mecab_new2()` seems not that reliable.
- Rename `Lattice->getBeginNodes()` to `Lattice->getBeginningNodes()`,
  `Lattice->nextStart()` to `Lattice->nextBeginning()` for consistency.
### Fixed
- Fix `Lattice->getBosNode()` and `Lattice->getEosNode()` not working.
- Fix some of issues when using `Lattice`.

[0.2.2]: https://github.com/ranvis/php-mecab-ffi/compare/v0.2.1..v0.2.2
## [0.2.2] - 2022-01-13
### Added
- Allow `null` as the default shlib name.
### Fixed
- Fix `Tagger` constructor with empty argument.
- Fix returning value of `Node->features()` and type of `Node->prob()`.

[0.2.1]: https://github.com/ranvis/php-mecab-ffi/compare/v0.2.0..v0.2.1
## [0.2.1] - 2020-12-19
### Added
- Add `Node->features()`, which is a better alternative to `Node->feature()`.
### Changed
- Change `Node` magic getter not to throw but assert for invalid properties.
### Fixed
- Optimize for OPcache.

[0.2.0]: https://github.com/ranvis/php-mecab-ffi/compare/v0.1.1..v0.2.0
## [0.2.0] - 2020-12-15
### Changed
- Rename `Node->wordCost()` to `Node->wCost()`.

[0.1.1]: https://github.com/ranvis/php-mecab-ffi/compare/v0.1.0..v0.1.1
## [0.1.1] - 2020-12-13
### Added
- Add preloader.
### Fixed
- Fix null character test.
- Fix to return stable instance on traversing `Node`.

[0.1.0]: https://github.com/ranvis/php-mecab-ffi/commits/v0.1.0
## [0.1.0] - 2020-12-16
### Added
- Initial release.
