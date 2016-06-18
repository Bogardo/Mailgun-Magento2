<?php

namespace Bogardo\Mailgun\Mail;

use Bogardo\Mailgun\Helper\Config as Config;
use InvalidArgumentException;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Mail\MessageInterface;
use Magento\Framework\Mail\Transport as MagentoTransport;
use Magento\Framework\Mail\TransportInterface;
use Magento\Framework\Phrase;
use Mailgun\Mailgun;
use Mailgun\Messages\MessageBuilder;
use Zend_Mail;

class Transport extends MagentoTransport implements TransportInterface
{

    /**
     * @var \Bogardo\Mailgun\Helper\Config
     */
    protected $config;

    /**
     * @var \Magento\Framework\Mail\MessageInterface|Zend_Mail
     */
    protected $message;

    /**
     * Transport constructor.
     *
     * @param \Magento\Framework\Mail\MessageInterface $message
     * @param null                                     $parameters
     *
     * @throws InvalidArgumentException
     */
    public function __construct(MessageInterface $message, $parameters = null)
    {
        parent::__construct($message, $parameters);

        $this->config = ObjectManager::getInstance()->create(Config::class);
        $this->message = $message;
    }

    /**
     * Send a mail using this transport
     *
     * @return void
     */
    public function sendMessage()
    {
        // If Mailgun Service is disabled, use the default mail transport
        if (!$this->config->enabled()) {
            parent::sendMessage();

            return;
        }

        $messageBuilder = $this->createMailgunMessage($this->parseMessage());

        $mailgun = new Mailgun($this->config->privateKey(), $this->getHttpClient(), $this->config->endpoint());
        $mailgun->setApiVersion($this->config->version());
        $mailgun->setSslEnabled($this->config->ssl());

        $mailgun->sendMessage($this->config->domain(), $messageBuilder->getMessage(), $messageBuilder->getFiles());
    }

    /**
     * @return array
     */
    protected function parseMessage()
    {
        $parser = new MessageParser($this->message);

        return $parser->parse();
    }

    /**
     * @return \Http\Client\HttpClient
     */
    protected function getHttpClient()
    {
        return new \Http\Adapter\Guzzle6\Client();
    }

    /**
     * @param array $message
     *
     * @return \Mailgun\Messages\MessageBuilder
     * @throws \Mailgun\Messages\Exceptions\TooManyParameters
     */
    protected function createMailgunMessage(array $message)
    {
        $builder = new MessageBuilder();
        $builder->setFromAddress($message['from']);
        $builder->setSubject($message['subject']);
        foreach ($message['to'] as $to) {
            $builder->addToRecipient($to);
        }

        foreach ($message['cc'] as $cc) {
            $builder->addCcRecipient($cc);
        }

        foreach ($message['bcc'] as $bcc) {
            $builder->addBccRecipient($bcc);
        }

        if ($message['html']) {
            $builder->setHtmlBody($message['html']);
        }

        if ($message['text']) {
            $builder->setTextBody($message['text']);
        }

        return $builder;
    }

}
