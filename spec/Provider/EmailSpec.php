<?php

namespace spec\Morrislaptop\ErrorTracker\Provider;

use Exception;
use Morrislaptop\ErrorTracker\Provider\Email\Body;
use Morrislaptop\ErrorTracker\Provider\Email\Compiler;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Swift_Mailer;
use Swift_Message;

class EmailSpec extends ObjectBehavior
{
    private $mailer;
    private $message;
    private $body;
    private $compiler;

    function let(Swift_Mailer $mailer, Swift_Message $message, Body $body, Compiler $compiler)
    {
        $this->mailer = $mailer;
        $this->message = $message;
        $this->body = $body;
        $this->compiler = $compiler;
        $this->beConstructedWith($mailer, $message, $body, $compiler);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Morrislaptop\ErrorTracker\Provider\Email');
    }

    function it_builds_compiles_and_sends()
    {
        // Arrange.
        $exception = new Exception('phpspec test');
        $extra = ['hi' => 'mum'];
        $this->body->build($exception, $extra)->willReturn('markdown');

        // Act.
        $this->report($exception, $extra);

        // Assert.
        $this->body->build($exception, $extra)->shouldHaveBeenCalled();
        $this->compiler->compile('markdown')->shouldHaveBeenCalled();
        $this->mailer->send($this->message)->shouldHaveBeenCalled();
    }
}
