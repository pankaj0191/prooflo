<?php

namespace App\Interactions\Settings\Profile;

use Illuminate\Support\Facades\Validator;
use App\Events\Profile\ContactInformationUpdated;
use App\Contracts\Interactions\Settings\Profile\UpdateContactInformation as Contract;

class UpdateContactInformation implements Contract
{
    /**
     * {@inheritdoc}
     */
    public function validator($user, array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function handle($user, array $data)
    {
        $user->forceFill([
            'name' => $data['name'],
            'email' => $data['email'],
        ])->save();

        event(new ContactInformationUpdated($user));

        return $user;
    }
}
