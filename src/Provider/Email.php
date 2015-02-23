<?php namespace Morrislaptop\ErrorTracker\Provider;

use Exception;
use Morrislaptop\ErrorTracker\Provider\Email\Body;
use Morrislaptop\ErrorTracker\Provider\Email\Compiler;
use Swift_Mailer;
use Swift_Message;

class Email extends AbstractProvider
{

    /**
     * @var Swift_Mailer
     */
    protected $mailer;

    /**
     * @var Swift_Message
     */
    protected $message;

    /**
     * @var Body
     */
    protected $body;

    /**
     * @var Compiler
     */
    protected $compiler;

    /**
     * @param Swift_Mailer $mailer
     * @param Swift_Message $message (object should be ready to go with addresses)
     * @param Body $body
     * @param Compiler $compiler
     */
    public function __construct(Swift_Mailer $mailer, Swift_Message $message, Body $body, Compiler $compiler)
    {
        $this->mailer        = $mailer;
        $this->message       = $message;
        $this->body          = $body;
        $this->compiler      = $compiler;
    }

    /**
     * Reports the exception to the SaaS platform
     *
     * @param Exception $e
     * @param array $extra
     * @return mixed
     */
    public function report(Exception $e, array $extra = [])
    {
        $message = $this->buildMessage($e, $extra);
        $this->mailer->send($message);
    }

    /**
     * @param $e
     * @param $extra
     * @return Swift_Message
     */
    protected function buildMessage(Exception $e, array $extra)
    {
        $this->message->setSubject('Exception: ' . $e->getMessage());
        $this->message->setBody($body = $this->body->build($e, $extra));
        $this->message->addPart($this->compiler->compile($body), 'text/html');

        return $this->message;
    }
}
