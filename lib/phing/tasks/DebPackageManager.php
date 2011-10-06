<?php

require_once dirname(__FILE__) . '/PackageManagerInterface.php';
require_once "phing/Task.php";


class DebPackageManager implements PackageManagerInterface
{
    /**
     * Inherited
     */
    public function isInstalled($packageName)
    {
        return false;
    }

    /**
     * Inherited
     */
    public function getInstallCommand()
    {
        return 'apt-get install';
    }
}
