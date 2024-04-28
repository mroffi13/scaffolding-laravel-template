<?php

namespace App;

use Illuminate\Support\Facades\Auth;

class MenuFilter
{
    public static function getSidebarMenu()
    {
        $menu = json_decode(json_encode(config('template.menu')));
        foreach ($menu as $item)
        {
            foreach (($item->sub_menu ?? []) as $idx => $submenu)
            {
                $submenu = self::filter($submenu);
            }
            $item = self::filter($item);
        }
        return json_decode(json_encode($menu), true);
    }

    public static function filter($item)
    {
        $user = Auth::user();
        if(!empty($item->permission) && !$user->isAbleTo($item->permission))
            $item->restricted = true;
        return $item;
    }
}
