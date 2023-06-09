<?php

namespace App\Interactions\Settings\PaymentMethod;

use App\User;
use App\Spark;
use App\Contracts\Repositories\UserRepository;
use App\Contracts\Repositories\TeamRepository;
use App\Contracts\Interactions\Settings\PaymentMethod\UpdatePaymentMethod;

class UpdateStripePaymentMethod implements UpdatePaymentMethod
{
    /**
     * {@inheritdoc}
     */
    public function handle($billable, array $data)
    {
        // Next, we need to check if this application is storing billing addresses and if so
        // we will update the billing address in the database so that any tax information
        // on the user will be up to date via the taxPercentage method on the billable.
        if (Spark::collectsBillingAddress()) {
            Spark::call(
                $this->updateBillingAddressMethod($billable),
                [$billable, $data]
            );
        }

        if (! $billable->stripe_id) {
            $billable->createAsStripeCustomer();
        }

        $billable->updateCard($data['stripe_token']);
    }

    /**
     * Get the repository class name for a given billable instance.
     *
     * @param  mixed  $billable
     * @return string
     */
    protected function updateBillingAddressMethod($billable)
    {
        return ($billable instanceof User
                    ? UserRepository::class
                    : TeamRepository::class).'@updateBillingAddress';
    }
}
