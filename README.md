# Magento 2 Mailgun Module

Send Magento's transactional e-mails with the Mailgun API service.

### Installation

Install using composer

```bash
composer require bogardo/mailgun-magento2
```

### Configuration

Module configuration can be found in:  `Stores -> Configuration -> Services -> Mailgun`

Fill in your Mailgun Domain and your API Keys.
These can be found in your [Mailgun control panel](https://mailgun.com/app/dashboard).

Next, enable the module by setting the `Enabled` option to `Yes`.

### Usage

All transactional email that are send by your Magento application are now send using the Mailgun API.
You can access the mail logs in your [Mailgun control panel](https://mailgun.com/app/logs).

## Changelog

#### 0.1.1
- Update README
    - Add installation instructions
    - Add changelog

#### 0.1.0
- Base Module
- Test Mode
- Debug Mode: Send messages to [Mailgun's Postbin](http://bin.mailgun.net/)
