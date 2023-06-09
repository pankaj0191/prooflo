<?php

namespace App\Http\Controllers\Settings\Billing;

use App\Spark;
use Illuminate\Http\Response;
use Laravel\Cashier\Http\Controllers\WebhookController;
use App\Events\Subscription\SubscriptionCancelled;
use App\Contracts\Repositories\LocalInvoiceRepository;
use App\Events\Teams\Subscription\SubscriptionCancelled as TeamSubscriptionCancelled;

class StripeWebhookController extends WebhookController
{
    use SendsInvoiceNotifications;

    /**
     * Handle a successful invoice payment from a Stripe subscription.
     *
     * By default, this e-mails a copy of the invoice to the customer.
     *
     * @param  array  $payload
     * @return \Illuminate\Http\Response
     */
    protected function handleInvoicePaymentSucceeded(array $payload)
    {
        $user = $this->getUserByStripeId(
            $payload['data']['object']['customer']
        );

        if (is_null($user)) {
            return $this->teamInvoicePaymentSucceeded($payload);
        }

        $invoice = $user->findInvoice($payload['data']['object']['id']);

        app(LocalInvoiceRepository::class)->createForUser($user, $invoice);

        $this->sendInvoiceNotification(
            $user, $invoice
        );

        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle a successful invoice payment from a Stripe subscription.
     *
     * @param  array  $payload
     * @return \Illuminate\Http\Response
     */
    protected function teamInvoicePaymentSucceeded(array $payload)
    {
        $team = Spark::team()->where(
            'stripe_id', $payload['data']['object']['customer']
        )->first();

        if (is_null($team)) {
            return;
        }

        $invoice = $team->findInvoice($payload['data']['object']['id']);

        app(LocalInvoiceRepository::class)->createForTeam($team, $invoice);

        $this->sendInvoiceNotification(
            $team, $invoice
        );

        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle a cancelled customer from a Stripe subscription.
     *
     * @param  array  $payload
     * @return \Illuminate\Http\Response
     */
    protected function handleCustomerSubscriptionDeleted(array $payload)
    {
        parent::handleCustomerSubscriptionDeleted($payload);

        $user = $this->getUserByStripeId($payload['data']['object']['customer']);

        if (! $user) {
            return $this->teamSubscriptionDeleted($payload);
        }

        event(new SubscriptionCancelled(
            $this->getUserByStripeId($payload['data']['object']['customer']))
        );

        return new Response('Webhook Handled', 200);
    }

    /**
     * Handle a cancelled customer from a Stripe subscription.
     *
     * @param  array  $payload
     * @return \Illuminate\Http\Response
     */
    protected function teamSubscriptionDeleted(array $payload)
    {
        $team = Spark::team()->where(
            'stripe_id', $payload['data']['object']['customer']
        )->first();

        if ($team) {
            $team->subscriptions->filter(function ($subscription) use ($payload) {
                return $subscription->stripe_id === $payload['data']['object']['id'];
            })->each(function ($subscription) {
                $subscription->markAsCancelled();
            });
        } else {
            return new Response('Webhook Handled', 200);
        }

        event(new TeamSubscriptionCancelled($team));

        return new Response('Webhook Handled', 200);
    }
}
