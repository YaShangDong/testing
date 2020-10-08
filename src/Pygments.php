<?php

declare(strict_types=1);

/**
 * This file is part of the ramsey/pygments library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Kazuyuki Hayashi <hayashi@valnur.net>
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

namespace YaSD\Pygments;

use Symfony\Component\Process\Process;

/**
 * A PHP wrapper for Pygments, the Python syntax highlighter
 * 
 * @package YaSD\Pygments
 */
class Pygments
{
    protected string $pygmentize;
    protected array $args;

    /**
     * Constructor
     * 
     * @param string $pygmentize The path to pygmentize command
     */
    public function __construct(string $pygmentize = 'pygmentize')
    {
        $this->pygmentize = $pygmentize;
        $this->args = [];
    }

    /**
     * Highlight code string
     * 
     * @param string $code The code to highlight
     * @param string|null $lexer The name of the lexer (php, html,...)
     * @param string|null $formatter The name of the formatter (html, ansi,...)
     * @param array|null $options An array of options
     * 
     * @return string 
     * 
     * @throws \Symfony\Component\Process\Exception\LogicException 
     * @throws \Symfony\Component\Process\Exception\RuntimeException 
     * @throws \Symfony\Component\Process\Exception\ProcessTimedOutException 
     * @throws \Symfony\Component\Process\Exception\ProcessSignaledException 
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException 
     */
    public function highlight(string $code, string $lexer = null, string $formatter = null, array $options = null): string
    {
        // lexer
        if ($lexer) {
            $this->addArgs('-l', $lexer);
        } else {
            $this->addArgs('-g');
        }

        // formatter
        if ($formatter) {
            $this->addArgs('-f', $formatter);
        }

        // options
        if ($options) {
            foreach ($options as $key => $value) {
                $this->addArgs('-P', "{$key}={$value}");
            }
        }

        $process = $this->getProcess()->setInput($code);
        return $this->getOutput($process);
    }

    /**
     * get style definition
     * 
     * @param string $style The name of the style (default, colorful,...)
     * @param string|null $prependedSelector The css selector
     * 
     * @return string 
     * 
     * @throws \Symfony\Component\Process\Exception\RuntimeException 
     * @throws \Symfony\Component\Process\Exception\ProcessTimedOutException 
     * @throws \Symfony\Component\Process\Exception\ProcessSignaledException 
     * @throws \Symfony\Component\Process\Exception\LogicException 
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException 
     */
    public function getCss(string $style = 'default', string $prependedSelector = null): string
    {
        $this->addArgs('-f', 'html');
        $this->addArgs('-S', $style);

        if ($prependedSelector) {
            $this->addArgs('-a', $prependedSelector);
        }

        $process = $this->getProcess();
        return $this->getOutput($process);
    }

    /**
     * Guesses a lexer name based solely on the given filename
     * 
     * @param string $fileName The file does not need to exist, or be readable.
     * 
     * @return string 
     * 
     * @throws \Symfony\Component\Process\Exception\RuntimeException 
     * @throws \Symfony\Component\Process\Exception\ProcessTimedOutException 
     * @throws \Symfony\Component\Process\Exception\ProcessSignaledException 
     * @throws \Symfony\Component\Process\Exception\LogicException 
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException 
     */
    public function guessLexer(string $fileName): string
    {
        $this->addArgs('-N', $fileName);
        $process = $this->getProcess();
        return \trim($this->getOutput($process));
    }

    /**
     * Gets a list of lexers
     * 
     * @return array 
     * 
     * @throws \Symfony\Component\Process\Exception\RuntimeException 
     * @throws \Symfony\Component\Process\Exception\ProcessTimedOutException 
     * @throws \Symfony\Component\Process\Exception\ProcessSignaledException 
     * @throws \Symfony\Component\Process\Exception\LogicException 
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException 
     */
    public function getLexers(): array
    {
        $this->addArgs('-L', 'lexer');
        $process = $this->getProcess();
        $output = $this->getOutput($process);
        return $this->parseList($output);
    }

    /**
     * Gets a list of formatters
     * 
     * @return array 
     * 
     * @throws \Symfony\Component\Process\Exception\RuntimeException 
     * @throws \Symfony\Component\Process\Exception\ProcessTimedOutException 
     * @throws \Symfony\Component\Process\Exception\ProcessSignaledException 
     * @throws \Symfony\Component\Process\Exception\LogicException 
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException 
     */
    public function getFormatters(): array
    {
        $this->addArgs('-L', 'formatter');
        $process = $this->getProcess();
        $output = $this->getOutput($process);
        return $this->parseList($output);
    }

    /**
     * Gets a list of styles
     * 
     * @return array 
     * 
     * @throws \Symfony\Component\Process\Exception\RuntimeException 
     * @throws \Symfony\Component\Process\Exception\ProcessTimedOutException 
     * @throws \Symfony\Component\Process\Exception\ProcessSignaledException 
     * @throws \Symfony\Component\Process\Exception\LogicException 
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException 
     */
    public function getStyles(): array
    {
        $this->addArgs('-L', 'style');
        $process = $this->getProcess();
        $output = $this->getOutput($process);
        return $this->parseList($output);
    }

    /**
     * add Process args
     * 
     * @param string $args 
     * 
     * @return $this
     */
    protected function addArgs(...$args): self
    {
        foreach ($args as $arg) {
            $this->args[] = $arg;
        }
        return $this;
    }

    /**
     * get Process
     * 
     * @return \Symfony\Component\Process\Process 
     */
    protected function getProcess(): Process
    {
        try {
            \array_unshift($this->args, $this->pygmentize);
            return new Process($this->args);
        } finally {
            $this->args = [];
        }
    }

    /**
     * get process' output
     * 
     * @param \Symfony\Component\Process\Process $process 
     * 
     * @return string 
     * 
     * @throws \Symfony\Component\Process\Exception\ProcessFailedException 
     * @throws \Symfony\Component\Process\Exception\LogicException 
     */
    protected function getOutput(Process $process): string
    {
        return $process->mustRun()->getOutput();
    }

    /**
     * parse output list
     * 
     * @param string $input 
     * 
     * @return array 
     */
    protected function parseList(string $input): array
    {
        $list = [];

        if (\preg_match_all('/^\*\s*([^:]+):\s*\r?\n\s*([^\r\n]+)/m', $input, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $names = explode(',', $match[1]);
                foreach ($names as $name) {
                    $list[trim($name)] = \trim($match[2]);
                }
            }
        }

        return $list;
    }
}
