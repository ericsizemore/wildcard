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
 *     Copyright (c) 2013-2023 Ronald Kirschler
 *
 * To see a list of changes in comparison to the original library {@see CHANGELOG.md}.
 */

namespace Esi\Wildcard\Tests;

use Esi\Wildcard\Wildcard;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\CoversClass;

#[CoversClass(Wildcard::class)]
class WildcardTest extends TestCase
{
    public function testStars(): void
    {
        self::assertTrue(Wildcard::create('*test.txt')->match('abc-test.txt'));
        self::assertTrue(Wildcard::create('*test.txt')->match('test.txt'));
        self::assertFalse(Wildcard::create('*test.txt')->match('est.txt'));

        self::assertTrue(Wildcard::create('test*.txt')->match('test-abc.txt'));
        self::assertTrue(Wildcard::create('test*.txt')->match('test.txt'));
        self::assertFalse(Wildcard::create('test*.txt')->match('tes.txt'));

        self::assertTrue(Wildcard::create('test.*')->match('test.txt'));
        self::assertTrue(Wildcard::create('test.*')->match('test.'));
        self::assertFalse(Wildcard::create('test.*')->match('test'));
    }

    public function testMarks(): void
    {
        self::assertTrue(Wildcard::create('?test.txt')->match('1test.txt'));
        self::assertFalse(Wildcard::create('?test.txt')->match('test.txt'));

        self::assertTrue(Wildcard::create('test?txt')->match('test.txt'));
        self::assertTrue(Wildcard::create('test?txt')->match('test-txt'));
        self::assertFalse(Wildcard::create('test?txt')->match('testtxt'));

        self::assertTrue(Wildcard::create('test.???')->match('test.txt'));
        self::assertFalse(Wildcard::create('test.???')->match('test.text'));
    }

    public function testMixed(): void
    {
        self::assertTrue(Wildcard::create('test*?txt')->match('test1.txt'));
    }

    public function testStartsAndEndsWith(): void
    {
        self::assertTrue(Wildcard::create('ab*ab')->match('abababab'));
        self::assertTrue(Wildcard::create('abab*abab')->match('abababab'));
        self::assertFalse(Wildcard::create('ababab*ababab')->match('abababab'));
    }
}
