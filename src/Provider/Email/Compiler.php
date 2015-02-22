<?php namespace Morrislaptop\ErrorTracker\Provider\Email;

use League\CommonMark\CommonMarkConverter;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class Compiler
{
    /**
     * @var CommonMarkConverter
     */
    protected $markdowner;

    /**
     * @var CssToInlineStyles
     */
    protected $inliner;

    /**
     * @param CommonMarkConverter $markdowner
     * @param CssToInlineStyles $inliner
     */
    function __construct(CommonMarkConverter $markdowner, CssToInlineStyles $inliner)
    {
        $this->markdowner = $markdowner;
        $this->inliner    = $inliner;
    }

    function compile($body)
    {
        $css  = file_get_contents(__DIR__ . '/../../../bower_components/github-markdown-css/github-markdown.css');
        $html = '<div class="markdown-body">' . $this->markdowner->convertToHtml($body) . '</div>';

        $this->inliner->setCSS($css);
        $this->inliner->setHTML($html);

        return $this->inliner->convert();
    }
}