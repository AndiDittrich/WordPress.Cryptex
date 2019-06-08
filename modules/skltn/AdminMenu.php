<?php
// ---------------------------------------------------------------------------------------------------------------
// -- WP-SKELETON AUTO GENERATED FILE - DO NOT EDIT !!!
// --
// -- Copyright (c) 2016-2019 Andi Dittrich
// -- https://github.com/AndiDittrich/WP-Skeleton
// --
// ---------------------------------------------------------------------------------------------------------------
// --
// -- This Source Code Form is subject to the terms of the Mozilla Public License, v. 2.0.
// -- If a copy of the MPL was not distributed with this file, You can obtain one at http://mozilla.org/MPL/2.0/.
// --
// ---------------------------------------------------------------------------------------------------------------
 
// Admin menu initialization utility

namespace Cryptex\skltn;

class AdminMenu{

    // setup admin menu
    public static function init($menu){

        // utility
        function addOnloadHandler($page, $menu){
            // add load handler
            add_filter('load-'.$page, function() use ($menu){
                // onload handler ?
                if (isset($menu['onLoad'])){
                    call_user_func($menu['onLoad']);
                }

                // resource handler ?
                if (isset($menu['resources'])){
                    call_user_func($menu['resources']);
                }

                // contextual help handler ?
                if (isset($menu['help'])){
                    call_user_func($menu['help']);
                }
            });
        }

        // initialize toplevel menu
        $optionsPage = add_menu_page(
            $menu['pagetitle'],
            $menu['title'],
            'administrator',
            $menu['slug'],
            function() use ($menu){
                // on output hook set ?
                if (isset($menu['render'])){
                    call_user_func($menu['render']);
                }

                // render settings view
                include(CRYPTEX_PLUGIN_PATH.'/views/admin/'.$menu['template'].'.phtml');
            }, 
            $menu['icon']
        );

        // add onLoad handler
        self::addOnloadHandler($optionsPage, $menu);
        
        // submenu items set ?
        if (!isset($menu['items'])){
            return;
        }

        // process submenu items
        foreach ($menu['items'] as $submenu){
            // initialize submenu
            $submenuPage = add_submenu_page(
                $menu['slug'],
                $submenu['pagetitle'],
                $submenu['title'],
                'administrator',
                $submenu['slug'],
                function() use ($submenu){
                    // on output hook set ?
                    if (isset($submenu['render'])){
                        call_user_func($submenu['render']);
                    }

                    // render settings view
                    include(CRYPTEX_PLUGIN_PATH.'/views/admin/'.$submenu['template'].'.phtml');
                }
            );

            // add onLoad handler
            self::addOnloadHandler($submenuPage, $submenu);
        }
    }

    
}