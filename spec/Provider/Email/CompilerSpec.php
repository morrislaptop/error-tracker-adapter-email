<?php

namespace spec\Morrislaptop\ErrorTracker\Provider\Email;

use League\CommonMark\CommonMarkConverter;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class CompilerSpec extends ObjectBehavior
{
    private $converter;
    private $inliner;

    function let(CommonMarkConverter $converter, CssToInlineStyles $inliner)
    {
        $this->converter = $converter;
        $this->inliner = $inliner;
        $this->beConstructedWith($converter, $inliner);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Morrislaptop\ErrorTracker\Provider\Email\Compiler');
    }

    function it_compiles_with_inlined_github_markdown_css()
    {
        // Arrange.
        $this->converter->convertToHtml('markdown')->willReturn('markdown');

        // Act.
        $this->compile('markdown');

        // Assert.
        $this->converter->convertToHtml('markdown')->shouldHaveBeenCalled();
        $this->inliner->setHTML('<div class="markdown-body">markdown</div>')->shouldHaveBeenCalled();
        $this->inliner->setCSS(Argument::type('string'))->shouldHaveBeenCalled();
        $this->inliner->convert()->shouldHaveBeenCalled();
    }
}
