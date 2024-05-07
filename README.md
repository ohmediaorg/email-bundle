# Overview

This bundle offers functionality to leverage email sending via a Symfony's
messenger system, while storing recently sent emails in the DB for debug/admin
purposes.

# Installation

Update `composer.json` by adding this to the `repositories` array:

```json
{
    "type": "vcs",
    "url": "https://github.com/ohmediaorg/email-bundle"
}
```

Then run `composer require ohmediaorg/email-bundle:dev-main`.

Import the routes in `config/routes.yaml`:

```yaml
oh_media_email:
    resource: '@OHMediaEmailBundle/config/routes.yaml'
```

Run `php bin/console make:migration` then run the subsequent migration.

# Configuration

Create `config/packages/oh_media_email.yml` with the following contents:

```yaml
oh_media_email:
    cleanup: '-1 year' # this is the default
    from:
        email: no-reply@website.com # required
        name: Website.com # required
    subject_prefix: '[WEBSITE.COM]' # optional
    header_background: '#ff5b16' # default
    link_color: '#ff5b16' # default
```

The value of `cleanup` should be a string to pass to `new DateTime()`. Emails
older than this DateTime will be deleted when the CRON job is ran.

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

The new Email will get sent once Symfony's messaging system has processed it.

# Email Templating

For convenience, some basic styles/layout are already provided utilizing the
`header_background` and `link_color` configuration values.

You can take advantage of the base template as follows:

```twig
{% extends '@OHMediaEmail/base.html.twig' %}

{% block content %}
{# custom email content goes here #}
{% endblock %}
```

The header and footer content can be customized by creating:

- `templates/bundles/OHMediaEmailBundle/include/header.html.twig`
- `templates/bundles/OHMediaEmailBundle/include/footer.html.twig`

## Custom Styles

If you want to override the styles completely, create
`templates/bundles/OHMediaEmailBundle/inline-css.html.twig`.

The contents of that file can be:

```twig
{% apply inline_css %}
    <style>
        {# here, define your CSS styles as usual #}
    </style>

    {{ html|raw }}
{% endapply %}
```

or

```twig
{% apply inline_css(source('@styles/email.css')) %}
    {{ html|raw }}
{% endapply %}
```

The path to the email styles can be whatever it needs to be.

_**Note:** It's recommended to have a separate set of styles for your emails. These
styles should be as simple as possible. They need to work in all sorts of email
programs!_

## Custom Everything

If you want to override absolutely everything, simply create your own base template and extend that!
