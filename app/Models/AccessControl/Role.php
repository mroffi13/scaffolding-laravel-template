<?php

namespace App\Models\AccessControl;

use Laratrust\Models\Role as RoleModel;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Role extends RoleModel
{
    use LogsActivity;
    public $guarded = [];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->logAll();
        // Chain fluent methods for configuration options
    }
}
