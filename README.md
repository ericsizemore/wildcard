Simple wildcard component
=========================

[![Build Status](https://scrutinizer-ci.com/g/ericsizemore/wildcard/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ericsizemore/wildcard/build-status/master)
[![Code Coverage](https://scrutinizer-ci.com/g/ericsizemore/wildcard/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ericsizemore/wildcard/?branch=master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ericsizemore/wildcard/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ericsizemore/wildcard/?branch=master)
[![Tests](https://github.com/ericsizemore/wildcard/actions/workflows/tests.yml/badge.svg)](https://github.com/ericsizemore/wildcard/actions/workflows/tests.yml)
[![PHPStan](https://github.com/ericsizemore/wildcard/actions/workflows/main.yml/badge.svg)](https://github.com/ericsizemore/wildcard/actions/workflows/main.yml)

[![Latest Stable Version](https://img.shields.io/packagist/v/esi/wildcard.svg)](https://packagist.org/packages/esi/wildcard)
[![Downloads per Month](https://img.shields.io/packagist/dm/esi/wildcard.svg)](https://packagist.org/packages/esi/wildcard)
[![License](https://img.shields.io/packagist/l/esi/wildcard.svg)](https://packagist.org/packages/esi/wildcard)

This project aims to provide a dead simple component for php to support wildcards. Wildcards are * (zero or more characters) and ? (exactly one character). The component is not tied to filenames. You can use it also for namespaces, urls and other strings.

## Why can't you just provide a simple function for this? 

Because of effectivity. When you create an instance of the `Wildcard`-class, you also "compile" the pattern. This means that I try to find the optimal test method for your later input. So if you run the same pattern more often in the same run, you could benefit from that optimization. If not, the Interface should still be simple enough to make you happy. If not, go wrap a function around it.

## Why not just use regular expressions?

Because there is no reason to use regular expressions for the most common figures:

`string*` means "starts with".
`*string` means "ends with".

So even if I use regular expressions to cover complex patterns, it is too pointless to use regular expressions for one of these. If you like to provide more speedups for such simple patterns, feel free to push me some.

## Composer:

```
composer require esi/wildcard ^1.0
```

## Example:

```php
use Esi\Wildcard\Wildcard;

(new Wildcard('test.*'))->match('test.txt');      // true
Wildcard::create('test.*')->match('test.txt');    // true
Wildcard::create('*.txt')->match('test.txt');     // true
Wildcard::create('*.*')->match('test.txt');       // true
Wildcard::create('test*txt')->match('test.txt');  // true
Wildcard::create('test?txt')->match('test.txt');  // true
Wildcard::create('t*.???')->match('test.txt');    // true
Wildcard::create('t*t?.txt')->match('test8.txt'); // true
```

## About

### Requirements

- PHP 8.2.0 or above.

### Submitting bugs and feature requests

Bugs and feature requests are tracked on [GitHub](https://github.com/ericsizemore/wildcard/issues)

Issues are the quickest way to report a bug. If you find a bug or documentation error, please check the following first:

* That there is not an Issue already open concerning the bug
* That the issue has not already been addressed (within closed Issues, for example)

### Contributing

Wildcard accepts contributions of code and documentation from the community. 
These contributions can be made in the form of Issues or [Pull Requests](http://help.github.com/send-pull-requests/) on the [Wildcard repository](https://github.com/ericsizemore/wildcard).

Wildcard is licensed under the MIT license. When submitting new features or patches to Wildcard, you are giving permission to license those features or patches under the MIT license.

Wildcard tries to adhere to PHPStan level 9 with strict rules and bleeding edge. Please ensure any contributions do as well.

#### Guidelines

Before we look into how, here are the guidelines. If your Pull Requests fail to pass these guidelines it will be declined, and you will need to re-submit when you’ve made the changes. This might sound a bit tough, but it is required for me to maintain quality of the code-base.

#### PHP Style

Please ensure all new contributions match the [PSR-12](https://www.php-fig.org/psr/psr-12/) coding style guide. The project is not fully PSR-12 compatible, yet; however, to ensure the easiest transition to the coding guidelines, I would like to go ahead and request that any contributions follow them.

#### Documentation

If you change anything that requires a change to documentation then you will need to add it. New methods, parameters, changing default values, adding constants, etc. are all things that will require a change to documentation. The change-log must also be updated for every change. Also, PHPDoc blocks must be maintained.

##### Documenting functions/variables (PHPDoc)

Please ensure all new contributions adhere to:
  * [PSR-5 PHPDoc](https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc.md)
  * [PSR-19 PHPDoc Tags](https://github.com/php-fig/fig-standards/blob/master/proposed/phpdoc-tags.md)

when documenting new functions, or changing existing documentation.

#### Branching

One thing at a time: A pull request should only contain one change. That does not mean only one commit, but one change - however many commits it took. The reason for this is that if you change X and Y but send a pull request for both at the same time, we might really want X but disagree with Y, meaning we cannot merge the request. Using the Git-Flow branching model you can create new branches for both of these features and send two requests.

### Author

Eric Sizemore - <admin@secondversion.com> - <https://www.secondversion.com>

### License

Wildcard is licensed under the MIT License - see the `LICENSE.md` file for details

### Acknowledgements / Credits

This repository is a fork of [rkrx/php-wildcards](https://github.com/rkrx/php-wildcards). Thanks to them and all the contributors!
