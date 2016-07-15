<?php
/**
 * Created by PhpStorm.
 * User: johnny
 * Date: 7/14/16
 * Time: 8:56 PM
 */

namespace Bogardo\Mailgun\Mail;


class Message extends \Magento\Framework\Mail\Message
{
    private $data = [
        'to' => [],
        'cc' => [],
        'bcc' => [],
    ];

    public function addTo($email, $name='')
    {
        $this->data['to'][$email] = $name;
        parent::addTo($email, $name);
    }

    public function addCc($email, $name='')
    {
        $this->data['cc'][$email] = $name;
        parent::addCc($email, $name);
    }

    public function addBcc($email, $name='')
    {
        $this->data['bcc'][$email] = $name;
        parent::addBcc($email, $name);
    }

    public function getToRecipients() {
        return $this->data['to'];
    }
    public function getCcRecipients() {
        return $this->data['cc'];
    }

    public function getBccRecipients() {
        return $this->data['bcc'];
    }
}