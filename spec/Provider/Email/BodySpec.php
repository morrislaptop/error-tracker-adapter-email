<?php

namespace spec\Morrislaptop\ErrorTracker\Provider\Email;

use PhpSpec\Exception\Example\SkippingException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;

class BodySpec extends ObjectBehavior
{
    private $cloner;
    private $dumper;

    function let(VarCloner $cloner, CliDumper $dumper)
    {
        $this->cloner = $cloner;
        $this->dumper = $dumper;
        $this->beConstructedWith($cloner, $dumper);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Morrislaptop\ErrorTracker\Provider\Email\Body');
    }

    function it_creates_a_good_email()
    {
        throw new SkippingException('Symfony dumper with output streams not possible with phpspec');
    }
}
