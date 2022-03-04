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

# Configuration

Create `config/packages/oh_media_email.yml` with the following contents:

```yaml
oh_media_email:
    cleanup: '-1 year' # this is the default
    from:
        email: no-reply@website.com # required
        name: Website.com # required
    subject_prefix: '[WEBSITE.COM]' # optional
```

The value of `cleanup` should be a string to pass to `new DateTime()`. Emails
older than this DateTime will be deleted.

The values of `from.email` and `from.name` will be used to create an instance of
`Util\EmailAddress`. This value will be passed to `setFrom()` on all emails.

The value of `subject_prefix` will be prepended to the subject of every Email.

# Creating Emails

Simply populate and save an Email entity:

```php
use OHMedia\EmailBundle\Entity\Email;
use OHMedia\EmailBundle\Util\EmailAddress;
use OHMedia\EmailBundle\Util\EmailAttachment;

$recipient = new EmailAddress('justin@ohmedia.ca', 'Justin Hoffman');

$formUserEmail = new EmailAddress($form->get('email'), $form->get('name'));

$email = new Email();
$email
    ->setSubject('Confirmation Email')
    ->setTemplate($template, $params)
    ->setTo($recipient)
    ->setReplyTo($formUserEmail)
;

$attachment = new EmailAttachment('/absolute/path/to/file.txt', 'Notes');

$email->setAttachments($attachment);

$em->persist($email);
$em->flush();
```

Don't bother using `setFrom()`. The value will get overridden. You can use
`setHtml` or `setTemplate` to populate the email content.

Various functions on this class are variadic (https://www.php.net/manual/en/functions.arguments.php#functions.variable-arg-list).

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
