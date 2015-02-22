<?php namespace tests\Morrislaptop\ErrorTracker\Provider;

use DomainException;
use League\CommonMark\CommonMarkConverter;
use Morrislaptop\ErrorTracker\Provider\Email;
use Morrislaptop\ErrorTracker\Provider\Email\Body;
use PHPUnit_Framework_TestCase;
use Swift_Mailer;
use Swift_Message;
use Swift_SendmailTransport;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;
use tests\Morrislaptop\ErrorTracker\TrackerTestCase;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class EmailTest extends PHPUnit_Framework_TestCase {

    public function testReport()
    {
        //  Arrange.
        $transport = new Swift_SendmailTransport();
        $mailer = new Swift_Mailer($transport);
        $message = new Swift_Message();
        $message->addTo('craig.michael.morris@gmail.com');
        $message->setFrom('craig.michael.morris@gmail.com');
        $body = new Body(new VarCloner(), new CliDumper());
        $compiler = new Email\Compiler(new CommonMarkConverter(), new CssToInlineStyles());
        $email = new Email($mailer, $message, $body, $compiler);
        $exception = new DomainException('Testing a domain exception');

        // Act.
        $email->report($exception, ['only' => 'testing']);
    }

}