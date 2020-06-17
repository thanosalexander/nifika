<div class="container text-center">
    <h3>We review and accept your consultation</h3>
    <h2 class="text-info">requests on a 24/7 basis</h2>
    <form class="rd-mailform text-center rd-mailform-small offset-top-30 offset-md-top-40 offset-lg-top-60 offset-xl-top-80" data-form-output="form-output-global" data-form-type="contact" method="post" action="<?= route("public.contact.send");  ?>">
        <div class="form-wrap">
            <label class="form-label" for="contact-name">Όνομα</label>
            <input class="form-input" id="contact-name" type="text" name="name" data-constraints="@Required">
        </div>
        <div class="form-wrap">
            <label class="form-label" for="contact-email-form">E-mail</label>
            <input class="form-input" id="contact-email-form" type="email" name="email" data-constraints="@Email @Required">
        </div>
        <div class="form-wrap">
            <label class="form-label" for="contact-message">Μήνυμα</label>
            <textarea class="form-input" id="contact-message" name="message" data-constraints="@Required"></textarea>
        </div>
        <button class="btn btn-info" type="submit">αποστολή</button>
        
        <input class="form-input" type="hidden" name="_token" value="<?= csrf_token();  ?>">
    </form>
</div>