<?php

namespace App\Jobs;

use App\ApiConsumer;
use App\Jobs\Job;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendApiResetKeyEmail extends Job implements ShouldQueue
{

    use InteractsWithQueue, SerializesModels;

    protected $apiConsumer;

    /**
     * SendApiResetKeyEmail constructor.
     *
     * @param ApiConsumer $apiConsumer
     */
    public function __construct(ApiConsumer $apiConsumer)
    {
        $this->apiConsumer = $apiConsumer;
    }

    /**
     * Send an email to the ApiConsumer with their Reset Key.
     *
     * @param Mailer $mailer
     */
    public function handle(Mailer $mailer)
    {
        $u = $this->apiConsumer;

        $mailer->send('emails.api_consumers.api-consumer-reset-key-email', ['api_consumer' => $u], function ($m) use ($u) {
            $m->to($u->email)->subject('Refresh Your ' . env('SITE_NAME') . ' API Token');
        });
    }
}
