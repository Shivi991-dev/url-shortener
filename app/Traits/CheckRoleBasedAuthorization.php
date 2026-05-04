<?php

namespace App\Traits;

trait CheckRoleBasedAuthorization
{
    public function checkRoleBasedAuthorization($roles = [])
    {
        if(!auth()->user()->hasAnyRole($roles)) {
            abort(403);
        }
        return true;
    }
}