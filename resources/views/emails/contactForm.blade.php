<?php

?>
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <style type="text/css" rel="stylesheet" media="all">
            /* Media Queries */
        </style>
    </head>
    <body>
        <h4><?= trans('public.contact.formTitle')?></h4>
        <p><?= trans('public.contact.form.fullname')?>: <?= e($fullname) ?></p>
        <p><?= trans('public.contact.form.email')?>: <?= e($email) ?></p>
        <p><?= trans('public.contact.form.subject')?>: <?= e($contactFormSubject) ?></p>
        <p><?= trans('public.contact.form.message')?>: <br><?= nl2br(e($contactFormMessage)) ?></p>
    </body>
</html>