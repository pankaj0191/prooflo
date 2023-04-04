<?php

namespace App\Policies;

use App\User;
use App\Policies\Policy;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubCategoryPolicy extends Policy
{
    use HandlesAuthorization;
    /**
     * The Permission key the Policy corresponds to.
     *
     * @var string
     */
    public static $key = 'sub_categories';
}