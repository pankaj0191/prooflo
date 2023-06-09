<?php

namespace App\Events\Prooflo\PaymentMethod;

class BillingAddressUpdated
{
    /**
     * The billable instance.
     *
     * @var mixed
     */
    public $billable;

    /**
     * Create a new event instance.
     *
     * @param mixed  $billable
     * @return void
     */
    public function __construct($billable)
    {
        $this->billable = $billable;
    }
}
