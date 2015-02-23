<?php namespace tests\Morrislaptop\ErrorTracker\Provider;

use DomainException;
use League\CommonMark\CommonMarkConverter;
use Morrislaptop\ErrorTracker\Provider\Email;
use Morrislaptop\ErrorTracker\Provider\Email\Body;
use Morrislaptop\ErrorTracker\Provider\Email\Compiler;
use PHPUnit_Framework_TestCase;
use Swift_Mailer;
use Swift_Message;
use GuzzleHttp\Client;
use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use TijsVerkoyen\CssToInlineStyles\CssToInlineStyles;

class EmailTest extends PHPUnit_Framework_TestCase
{
    /**
     * The Guzzle client.
     *
     * @var Client
     */
    protected $mailtrap;

    public function setUp()
    {
        $this->mailtrap = new Client([
            'base_url' => 'https://mailtrap.io/api/v1/',
            'defaults' => [
                'headers' => ['Api-Token' => getenv('MAILTRAP_TOKEN')]
            ]
        ]);
    }

    public function testReport()
    {
        //  Arrange.
        $transport = new \Swift_SmtpTransport();
        $transport->setHost('mailtrap.io');
        $transport->setPort(2525);
        $transport->setUsername(getenv('MAILTRAP_USERNAME'));
        $transport->setPassword(getenv('MAILTRAP_PASSWORD'));
        $mailer = new Swift_Mailer($transport);

        $message = new Swift_Message();
        $message->addTo('craig.michael.morris@gmail.com');
        $message->setFrom('craig.michael.morris@gmail.com');

        $body     = new Body(new VarCloner(), new CliDumper());
        $compiler = new Compiler(new CommonMarkConverter(), new CssToInlineStyles());
        $email    = new Email($mailer, $message, $body, $compiler);

        $exception = new DomainException('Testing a domain exception');
        $extra     = ['only' => 'testing12321'];

        // Act.
        $email->report($exception, $extra);

        // Assert.
        $message = $this->mailtrap->get('inboxes/' . getenv('MAILTRAP_INBOX'). '/messages')->json()[0];
        $this->assertSame('Exception: Testing a domain exception', $message['subject']);

        $this->assertContains('$email->report($exception, $extra);', $message['text_body']);
        $this->assertContains("exception 'DomainException' with message 'Testing a domain exception'", $message['text_body']);
        $this->assertContains('{main}', $message['text_body']);
        $this->assertContains('"only" => "testing12321"', $message['text_body']);
        $this->assertContains('_SERVER', $message['text_body']);
    }

    public function tearDown()
    {
        $this->mailtrap->patch('inboxes/' . getenv('MAILTRAP_INBOX'). '/clean', ['future' => true]);
    }

}