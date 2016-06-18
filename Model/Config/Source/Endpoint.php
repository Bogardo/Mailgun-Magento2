<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * Used in creating options for Yes|No|Specified config value selection
 *
 */
namespace Bogardo\Mailgun\Model\Config\Source;

class Endpoint implements \Magento\Framework\Option\ArrayInterface
{

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 'api.mailgun.net', 'label' => __('Mailgun API (live)')],
            ['value' => 'bin.mailgun.net', 'label' => __('Mailgun Postbin (debug)')]
        ];
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'api.mailgun.net' => __('Mailgun API (live)'),
            'bin.mailgun.net' => __('Mailgun Postbin (debug)')
        ];
    }
}
