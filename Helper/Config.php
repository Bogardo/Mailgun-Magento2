<?php

namespace Bogardo\Mailgun\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;

class Config extends AbstractHelper
{

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        parent::__construct($context);

        $this->scopeConfig = $context->getScopeConfig();
    }

    /**
     * @return bool
     */
    public function enabled()
    {
        return (bool) $this->scopeConfig->getValue('bogardo_mailgun/api/enabled', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function domain()
    {
        return $this->scopeConfig->getValue('bogardo_mailgun/api/domain', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function privateKey()
    {
        return $this->scopeConfig->getValue('bogardo_mailgun/api/private_key', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function publicKey()
    {
        return $this->scopeConfig->getValue('bogardo_mailgun/api/public_key', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function testMode()
    {
        return (bool) $this->scopeConfig->getValue('bogardo_mailgun/general/test_mode', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function endpoint()
    {
        return $this->scopeConfig->getValue('bogardo_mailgun/developer/endpoint', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return string
     */
    public function version()
    {
        if ($this->endpoint() == 'api.mailgun.net') {
            return 'v3';
        }

        return $this->scopeConfig->getValue('bogardo_mailgun/developer/postbin_key', ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return bool
     */
    public function ssl()
    {
        return $this->endpoint() == 'api.mailgun.net' ? true : false;
    }

}
