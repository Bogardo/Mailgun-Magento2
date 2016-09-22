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
            'to' => $this->stringifyRecipients($this->parseRecipients('To')),
            'cc' => $this->stringifyRecipients($this->parseRecipients('Cc')),
            'bcc' => $this->stringifyRecipients($this->parseRecipients('Bcc')),
            'html' => $html ?: null,
            'text' => $text ?: null,
            'attachment' => []
        ];
    }

    /**
     * Allows for multiple bcc, to,
     * and cc emails to be sent out
     * 
     * @param $addresses
     *
     * @return array
     */

    protected function stringifyRecipients($addresses)
    {
        if(sizeof($addresses) > 1) {
            $addresses = implode(",", $addresses);
            return array($addresses);
        }
        else {
            return $addresses;
        }
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

            if (preg_match('/<(.*)>/', $recipient, $matches)){
                $recipientAddress = $matches[1];
            } else {
                $recipientAddress = $recipient;
            }

            if (in_array($recipientAddress, $all)) {
                $result[] = trim($recipient);
            }
        }

        return $result;
    }
}
