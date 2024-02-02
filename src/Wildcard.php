<?php

declare(strict_types=1);

/**
 * Wildcard - A simple wildcard matcher.
 *
 * @author    Eric Sizemore <admin@secondversion.com>
 * @version   1.0.0
 * @copyright (C) 2024 Eric Sizemore
 * @license   The MIT License (MIT)
 *
 * Copyright (C) 2024 Eric Sizemore<https://www.secondversion.com/>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to
 * deal in the Software without restriction, including without limitation the
 * rights to use, copy, modify, merge, publish, distribute, sublicense, and/or
 * sell copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/**
 * Esi\Wildcard is a fork of rkrx/php-wildcards (https://github.com/rkrx/php-wildcards) which is:
 *     Copyright (c) 2014-2023 Ronald Kirschler
 *
 * To see a list of changes in comparison to the original library {@see CHANGELOG.md}.
 */

namespace Esi\Wildcard;

use Closure;
use RuntimeException;

use function explode;
use function implode;
use function ltrim;
use function preg_last_error;
use function preg_match;
use function preg_quote;
use function preg_replace;
use function preg_split;
use function rtrim;
use function sprintf;
use function str_contains;
use function str_ends_with;
use function str_starts_with;
use function strlen;

use const PREG_BACKTRACK_LIMIT_ERROR;
use const PREG_BAD_UTF8_ERROR;
use const PREG_BAD_UTF8_OFFSET_ERROR;
use const PREG_INTERNAL_ERROR;
use const PREG_JIT_STACKLIMIT_ERROR;
use const PREG_RECURSION_LIMIT_ERROR;
use const PREG_SPLIT_DELIM_CAPTURE;

/**
 * @see \Esi\Wildcard\Tests\WildcardTest
 */
class Wildcard
{
    /**
     * Compiled function.
     */
    private Closure $function;

    /**
     * Create a static instance for a given pattern.
     */
    public static function create(string $pattern): Wildcard
    {
        return new static($pattern);
    }

    /**
     * Constructor.
     */
    final public function __construct(string $pattern)
    {
        $this->compile($pattern);
    }

    /**
     * Runs the compiled function (depending on a given pattern {@see $this->compile()}) on a given string.
     */
    public function match(string $string): bool
    {
        $callFunction = $this->function;

        return $callFunction($string);
    }

    /**
     * Compiles the function to be called when performing the pattern match on a given string.
     */
    private function compile(string $pattern): void
    {
        $pattern        = (string) preg_replace('/\\*+/', '*', $pattern);
        $doesNotContain = !str_contains($pattern, '?');

        match (true) {
            preg_match('/^[^\\*]+\\*$/', $pattern) === 1 && $doesNotContain        => $this->initStartsWith($pattern),
            preg_match('/^\\*[^\\*]+$/', $pattern) === 1 && $doesNotContain        => $this->initEndsWith($pattern),
            preg_match('/^[^\\*]+\\*[^\\*]+$/', $pattern) === 1 && $doesNotContain => $this->initStartsAndEndsWith($pattern),
            default                                                                => $this->initRegExp($pattern)
        };
    }

    /**
     * Defines {@see $this->function} with a function to determine if a string starts with a given pattern.
     */
    private function initStartsWith(string $startsWith): void
    {
        $startsWith = rtrim($startsWith, '*');

        $this->function = static fn (string $string): bool => str_starts_with($string, $startsWith);
    }

    /**
     * Defines {@see $this->function} with a function to determine if a string ends with a given pattern.
     */
    private function initEndsWith(string $endsWith): void
    {
        $endsWith = ltrim($endsWith, '*');

        $this->function = static fn (string $string): bool => str_ends_with($string, $endsWith);
    }

    /**
     * Defines {@see $this->function} with a function to determine if a string starts & ends with a given pattern.
     */
    private function initStartsAndEndsWith(string $pattern): void
    {
        [$startsWith, $endsWith] = explode('*', $pattern);

        $this->function = static function (string $string) use ($startsWith, $endsWith): bool {
            $stringLength = strlen($string);
            $bothLength   = strlen($startsWith) + strlen($endsWith);

            if ($bothLength > $stringLength) {
                return false;
            }

            return str_starts_with($string, $startsWith) && str_ends_with($string, $endsWith);
        };
    }

    /**
     * Defines {@see $this->function} with a function to determine if a string matches a given pattern.
     */
    private function initRegExp(string $pattern): void
    {
        $parts = preg_split('/([\\?\\*])/', $pattern, -1, PREG_SPLIT_DELIM_CAPTURE);

        if ($parts === false) {
            //@codeCoverageIgnoreStart
            $pregError = match(preg_last_error()) {
                PREG_INTERNAL_ERROR => 'internal PCRE error',
                PREG_BACKTRACK_LIMIT_ERROR => 'backtrack limit was exhausted',
                PREG_RECURSION_LIMIT_ERROR => 'recursion limit was exhausted',
                PREG_BAD_UTF8_ERROR => 'malformed UTF-8 data ',
                PREG_BAD_UTF8_OFFSET_ERROR => 'the offset didn\'t correspond to the begin of a valid UTF-8 code point',
                PREG_JIT_STACKLIMIT_ERROR => 'failed due to limited JIT stack space',
                default => 'unknown'
            };

            throw new RuntimeException(sprintf('PCRE error on preg_split: %s', $pregError));
            //@codeCoverageIgnoreEnd
        }

        foreach ($parts as &$part) {
            $part = match($part) {
                '*'     => '.*',
                '?'     => '.',
                default => preg_quote($part, '/')
            };
        }

        $pattern = implode('', $parts);

        $this->function = static fn (string $string): bool => (bool) preg_match(sprintf('/^%s$/', $pattern), $string);
    }
}
