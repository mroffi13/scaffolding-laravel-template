<?php

namespace App\Models\AccessControl;

use Laratrust\Models\Permission as PermissionModel;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Permission extends PermissionModel
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
