## CHANGELOG
A not so exhaustive list of changes for each release.

For a more detailed listing of changes between each version, 
you can use the following url: https://github.com/ericsizemore/wildcard/compare/v1.0.0...v1.0.1. 

Simply replace the version numbers depending on which set of changes you wish to see.

### 1.0.1 ()

  * Updated unit tests to use `assertTrue` and `assertFalse` instead of `assertEquals`.
  * Added the use of the PHPUnit `CoversClass` attribute.
  * Minor CS fixes.

### 1.0.0 (2024-02-02)

  * Forked from `rkrx/php-wildcards`(https://github.com/rkrx/php-wildcards)
  * Updated project namespace and renamed class from `Pattern` to `Wildcard`.
  * Updated composer.json
    * Bumped minimum PHP version to 8.2
    * Added dev-dependencies for PHP-CS-Fixer, Rector, and PHPStan (w/extensions for phpunit, strict rules)
    * Updated PHPUnit to 10.5+ 
  * Created imports for all used functions, constants, and class names.
  * Added Github workflows for testing and static analysis.
  * Removed `Pattern::$helpers` and `Pattern::initHelpers()` as it is not needed in PHP 8.
  * Cleaned up code and refactored to use newer PHP 8 features per PHP-CS-Fixer and Rector recommendations.
    * Should now adhere to PER and PSR-12
  * Updated README.md