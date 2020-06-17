<?php

namespace App\Mail;

use App\Logic\App\AppManager;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactFormMail extends Mailable {

    use Queueable,
        SerializesModels;

    /** The fullname.
     * @var string */
    protected $fullname;

    /** The email.
     * @var string */
    protected $email;

    /** The contactFormSubject.
     * @var string */
    protected $contactFormSubject;

    /** The message.
     * @var string */
    protected $contactFormMessage;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($fullname, $email, $subject, $message) {
        $this->fullname = $fullname;
        $this->email = $email;
        $this->contactFormSubject = $subject;
        $this->contactFormMessage = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build() {
        return $this->view('emails.contactForm')
                        ->subject(config('app.name'). ' - ' . trans('public.contact.formTitle'))
                        ->with('fullname', $this->fullname)
                        ->with('email', $this->email)
                        ->with('contactFormSubject', $this->contactFormSubject)
                        ->with('contactFormMessage', $this->contactFormMessage)
                        ->from(config('mail.from.address'), config('mail.from.name'));
    }

}
