<?php

namespace Bogardo\Mailgun\Mail;

use Magento\Framework\Mail\MessageInterface;

class MessageParser
{

    /**
     * @var \Magento\Framework\Message\MessageInterface|\Magento\Framework\Mail\Message
     */
    protected $message;

    /**
     * @param \Magento\Framework\Mail\MessageInterface $message
     */
    public function __construct(MessageInterface $message)
    {
        $this->message = $message;
    }

    /**
     * @return array
     */
    public function parse()
    {
        $eol = "\n";

        $html = "";
        $text = "";

        $htmlPart = $this->message->getBodyHtml();
        if ($htmlPart) {
            $html = $htmlPart->getContent($eol);
        }
        $textPart = $this->message->getBodyText();
        if ($textPart) {
            $text = $textPart->getContent($eol);
        }

        $text = quoted_printable_decode($text);
        $html = quoted_printable_decode($html);

        return [
            'from' => $this->message->getFrom(),
            'reply-to' => $this->message->getReplyTo(),
            'subject' => $this->message->getSubject(),
            'to' => $this->parseRecipients('To'),
            'cc' => $this->parseRecipients('Cc'),
            'bcc' => $this->parseRecipients('Bcc'),
            'html' => $html ?: null,
            'text' => $text ?: null,
            'attachment' => []
        ];
    }

    /**
     * @param string $type
     *
     * @return array
     */
    protected function parseRecipients($type)
    {
        $all = $this->message->getRecipients();

        $headers = $this->message->getHeaders();

        $recipients = isset($headers[$type]) ? $headers[$type] : [];

        $result = [];
        foreach ($recipients as $key => $recipient) {
            if ($key === 'append') {
                continue;
            }

            $result[] = trim($recipient);
        }

        return $result;
    }
}
