# Overview

This bundle offers functionality to leverage email sending via a CRON job.

# Installation

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

# Creating Emails

Simple populate and save a an Email entity:

```php
use OHMedia\EmailBundle\Entity\Email;
use OHMedia\EmailBundle\Util\EmailAddress;

$recipient = new EmailAddress('justin@ohmedia.ca', 'Justin Hoffman');

$email = new Email();
$email
    ->setSubject('Confirmation Email')
    
    // the email can be text-based
    ->setText($text)
    
    // or the email can be HTML-based
    ->setHtml($html)
    
    // create an HTML-based email using Twig templates
    ->setTemplate($template, $params)
    
    // the functions setTo, setCc, setBcc are all variadric
    ->setTo($recipient)
;
```

The new Email will get sent the next time CRON runs.

# Email Styles

Email styles need to be applied inline. Create a file called
`src/Resources/OHMediaEmail/inline-css.html.twig`.

The contents of that file can be:

```twig
{% apply inline_css %}
    <style>
        {# here, define your CSS styles as usual #}
    </style>

    {{{ html }}}
{% endapply %}
```

or 

```twig
{% apply inline_css(source('@styles/email.css')) %}
    {{{ html }}}
{% endapply %}
```

The path to the email styles can be whatever it needs to be.

_*Note:* It's recommended to have a separate set of styles for your emails. These
styles should be as simple as possible. They need to work in all sorts of email
programs!_
