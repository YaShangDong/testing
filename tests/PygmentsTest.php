<?php

namespace YaSD\Pygments\Tests;

use PHPUnit\Framework\TestCase;
use YaSD\Pygments\Pygments;

class PygmentsTest extends TestCase
{
    protected Pygments $pygments;

    protected function setUp(): void
    {
        $this->pygments = new Pygments();
    }

    public function provideCodeFiles()
    {
        $files = ['class.php', 'inline.php'];
        $dirResource = __DIR__ . '/resource/';

        foreach ($files as $file) {
            yield $file => [\sprintf('%s/%s', $dirResource, $file)];
        }
    }

    /**
     * @dataProvider provideCodeFiles
     */
    public function testHighlight($file)
    {
        $code = \file_get_contents($file);
        $lexer = \pathinfo($file, \PATHINFO_EXTENSION);
        $regexp = '/\<div class\="highlight"\>.*\<pre\>.*\<span class\="[^"]+">/s';

        $this->assertMatchesRegularExpression($regexp, $this->pygments->highlight($code, null, 'html'));
        $this->assertMatchesRegularExpression($regexp, $this->pygments->highlight($code, $lexer, 'html'));
        $this->assertMatchesRegularExpression($regexp, $this->pygments->highlight($code, $lexer, 'html', ['startinline' => true]));
    }

    public function testGetCss()
    {
        $this->assertStringContainsString('.hll {', $this->pygments->getCss());
        $this->assertStringContainsString('.mycode .hll {', $this->pygments->getCss('monokai', '.mycode'));
    }

    public function testGetLexers()
    {
        $lexers = $this->pygments->getLexers();
        $this->assertArrayHasKey('python', $lexers);
    }

    public function testGetFormatters()
    {
        $formatters = $this->pygments->getFormatters();
        $this->assertArrayHasKey('html', $formatters);
    }

    public function testGetStyles()
    {
        $styles = $this->pygments->getStyles();
        $this->assertArrayHasKey('monokai', $styles);
    }

    public function testGuessLexer()
    {
        $this->assertEquals('php', $this->pygments->guessLexer('index.php'));
        $this->assertEquals('go', $this->pygments->guessLexer('main.go'));
    }
}
