<?php

declare(strict_types=1);

namespace App\Mail;

use App\Models\EmailTemplateSetting;
use App\Utilities\Substitution;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use const null;

class Mail extends Mailable
{
    use Queueable;
    use SerializesModels;

    protected function buildMessage(
        string $key,
        array $data = [],
        ?string $view = null,
        ?string $subject = null,
    ): Mail {
        $template = $this->getTemplate($key);

        if (null === $view) {
            $view = 'mail.default';
        }

        if (null !== $subject) {
            $this->subject($subject);
        }

        if (null === $template) {
            return $this->view($view, $data);
        }

        if ($subject = $template->getAttribute('subject')) {
            $this->subject($subject);
        }

        if (!($body = $template->getAttribute('body'))) {
            return $this->view($view, $data);
        }

        $substitutions = new Substitution();
        $substitutions->setSubstitutions($data);

        return $this->view('mail.basic', [
            'content' => $substitutions->substitute($body),
        ]);
    }

    protected function getTemplate(string $key): ?EmailTemplateSetting
    {
        return EmailTemplateSetting::query()->where('key', $key)->first();
    }
}
