<?php

defined('BASEPATH') or exit('No direct script access allowed');

class InitModules
{
    /**
     * Early init modules features
     */
    public function handle()
    {
        // Include the App_modules.php file
        $appModulesPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'App_modules.php';
        if (file_exists($appModulesPath)) {
            include_once($appModulesPath);
        } else {
            trigger_error("File not found: $appModulesPath", E_USER_WARNING);
        }

        // Load the directory helper so the directory_map function can be used
        $directoryHelperPath = __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'helpers' . DIRECTORY_SEPARATOR . 'directory_helper.php';
        if (file_exists($directoryHelperPath)) {
            include_once($directoryHelperPath);
        } else {
            trigger_error("File not found: $directoryHelperPath", E_USER_WARNING);
        }

        // Handle modules
        foreach (\App_modules::get_valid_modules() as $module) {
            $excludeUrisPath = $module['path'] . 'config' . DIRECTORY_SEPARATOR . 'csrf_exclude_uris.php';

            if (file_exists($excludeUrisPath)) {
                $uris = include_once($excludeUrisPath);

                if (is_array($uris)) {
                    hooks()->add_filter('csrf_exclude_uris', function ($current) use ($uris) {
                        return array_merge($current, $uris);
                    });
                }
            }
        }
    }
}
