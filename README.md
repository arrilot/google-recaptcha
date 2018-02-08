[![Latest Stable Version](https://poser.pugx.org/arrilot/google-recaptcha/v/stable.svg)](https://packagist.org/packages/arrilot/google-recaptcha/)
[![Total Downloads](https://img.shields.io/packagist/dt/arrilot/google-recaptcha.svg?style=flat)](https://packagist.org/packages/Arrilot/google-recaptcha)
[![Build Status](https://img.shields.io/travis/arrilot/google-recaptcha/master.svg?style=flat)](https://travis-ci.org/arrilot/google-recaptcha)

# Abstraction Layer for google reCAPTCHA 2

## Installation

1) `composer require arrilot/google-recaptcha`

2) Somewhere during bootstrap

```php
\Arrilot\GoogleRecaptcha\Recaptcha::getInstance()
    ->setPublicKey('6Lf1_...')
    ->setSecretKey('6Lf1_...')
    ->setLanguage('ru');
```

## Usage example

```php
<?php

function recaptcha()
{
    return \Arrilot\GoogleRecaptcha\Recaptcha::getInstance();
}
?>

<? if (isset($_POST['g-recaptcha-response'])): ?>

    <h2>POST data</h2>
    <kbd><pre><? var_export($_POST); ?></pre></kbd>
    <? if (recaptcha()->verify()): ?>
        <h2 style="color:green">Success!</h2>
    <? else: ?>
        <h2 style="color:red">Something went wrong</h2>
        <pre><? var_export(recaptcha()->getErrors()); ?></pre>
    <? endif ?>

<? else: ?>

    <p>Complete the reCAPTCHA then submit the form.</p>
    <form action="/captcha/index2.php" method="post">
        <fieldset>
            <legend>An example form</legend>
            <p>Example input A: <input type="text" name="ex-a" value="foo"></p>
            <p>Example input B: <input type="text" name="ex-b" value="bar"></p>
            
            <?= recaptcha()->getHtml(['size' => 'compact']) ?>
            <?= recaptcha()->getScript() ?>
            <p><input type="submit" value="Submit" /></p>
        </fieldset>
    </form>

<?endif; ?>
```