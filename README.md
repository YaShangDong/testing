# ramsey/pygments

[![Source Code][badge-source]][github]
[![Latest Version][badge-release]][packagist]
[![Software License][badge-license]][license]
[![Build Status][badge-build]][travisci]
[![Coverage Status][badge-coverage]][coveralls]
[![Total Downloads][badge-downloads]][packagist]

ramsey/pygments is a PHP wrapper for [Pygments][], the Python syntax highlighter, forked from the [Pygments.php][kzykhys-pygments] project.

This project adheres to a [Contributor Code of Conduct][conduct]. By participating in this project and its community, you are expected to uphold this code.

## Requirements

* PHP 5.5.9 or greater (including PHP 7)
* Python
* Pygments (`pip install Pygments`)

Python and Pygments versions supported:

| Pygments:  | 1.6 | 2.0 | 2.1 | 2.2 |
| :--------- | :-: | :-: | :-: | :-: |
| Python 2.6 | ✔   | ✔   | ✔   | -   |
| Python 2.7 | ✔   | ✔   | ✔   | ✔   |
| Python 3.2 | ✔   | -   | -   | -   |
| Python 3.3 | ✔   | ✔   | ✔   | ✔   |
| Python 3.4 | ✔   | ✔   | ✔   | ✔   |
| Python 3.5 | ✔   | ✔   | ✔   | ✔   |
| Python 3.6 | ✔   | ✔   | ✔   | ✔   |

## Installation

The preferred method of installation is via [Packagist][] and [Composer][]. Run the following command to install the package and add it as a requirement to your project's `composer.json`:

```
composer require ramsey/pygments
```

## Usage

### Highlight source code

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments();
$html = $pygments->highlight(file_get_contents('index.php'), 'php', 'html');
$text = $pygments->highlight('package main', 'go', 'ansi');
```

### Generate CSS

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments();
$css = $pygments->getCss('monokai');
$prefixedCss = $pygments->getCss('default', '.syntax');
```

### Guesses lexer name

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments();
$pygments->guessLexer('foo.rb'); // ruby
```

### Get a list of lexers/formatters/styles

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments();
$pygments->getLexers()
$pygments->getFormatters();
$pygments->getStyles();
```

### Set a custom `pygmentize` path

``` php
use Ramsey\Pygments\Pygments;

$pygments = new Pygments('/path/to/pygmentize');
```

## Copyright and license

Copyrights for portions of ramsey/pygments are held by [Kazuyuki Hayashi][kzykhys] as part of [Pygments.php][kzykhys-pygments]. All other copyrights for ramsey/pygments are held by [Ben Ramsey][ramsey]. Please see [LICENSE][] for more information.


[badge-build]: https://img.shields.io/travis/ramsey/pygments/master.svg?style=flat-square
[badge-coverage]: https://img.shields.io/coveralls/ramsey/pygments/master.svg?style=flat-square
[badge-downloads]: https://img.shields.io/packagist/dt/ramsey/pygments.svg?style=flat-square
[badge-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[badge-release]: https://img.shields.io/packagist/v/ramsey/pygments.svg?style=flat-square
[badge-source]: https://img.shields.io/badge/source-ramsey/pygments-blue.svg?style=flat-square
[composer]: https://getcomposer.org
[conduct]: https://github.com/ramsey/pygments/blob/master/CODE_OF_CONDUCT.md
[coveralls]: https://coveralls.io/r/ramsey/pygments?branch=master
[github]: https://github.com/ramsey/pygments
[kzykhys-pygments]: https://github.com/kzykhys/Pygments.php
[kzykhys]: https://github.com/kzykhys
[license]: https://github.com/ramsey/pygments/blob/master/LICENSE
[packagist]: https://packagist.org/packages/ramsey/pygments
[pygments]: http://pygments.org/
[ramsey]: https://benramsey.com
[travisci]: https://travis-ci.org/ramsey/pygments
