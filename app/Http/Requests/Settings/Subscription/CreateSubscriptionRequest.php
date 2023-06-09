<?php

namespace App\Http\Requests\Settings\Subscription;

use Illuminate\Foundation\Http\FormRequest;
use App\Contracts\Repositories\CouponRepository;
use App\Http\Requests\Settings\Subscription\DeterminesPlanEligibility;

class CreateSubscriptionRequest extends FormRequest
{
    use DeterminesPlanEligibility;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Validate the coupon on the request.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    protected function validateCoupon($validator)
    {
        if (! app(CouponRepository::class)->valid($this->coupon)) {
            $validator->errors()->add('coupon', __('This coupon code is invalid.'));
        }
    }
}
