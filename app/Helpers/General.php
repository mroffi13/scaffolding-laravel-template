<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class General
{
    public static function checkPermission(array|string $permissions)
    {
        $user = Auth::user();
        if(!$user->isAbleTo($permissions))
        {
            abort(403, 'You don\'t have permission to perform this action.');
        }
    }
}
