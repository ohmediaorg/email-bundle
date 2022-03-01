Overview
========

This bundle offers functionality to leverage email sending via a CRON job.

Installation
============

Enable the bundle in `config/bundles.php`:

```php
return [
    // ...
    OHMedia\EmailBundle\OHMediaEmailBundle::class => ['all' => true],
];
```

Make and run the migration:

```bash
$ php bin/console make:migration
$ php bin/console doctrine:migrations:migrate
```

Create the CRON job:

```bash
* * * * * /path/to/php /path/to/symfony/bin/console ohmedia:email:send
```

Creating Emails
===============

Using the helper:

```php
use OHMedia\EmailBundle\Service\EmailHelper;

class Controller
{
    public function sendEmail(EmailHelper $helper)
    {
        $helper->send($subject, $template, $parameters, $to);
    }
}
```

When using the helper, email settings are used to set the
subject prefix, and the `from` component of the email.

The new Email will get sent the next time CRON runs.
