<?php

require_once dirname(__FILE__) . '/PackageManagerInterface.php';
require_once "phing/Task.php";


class MacPortsPackageManager implements PackageManagerInterface
{
    /**
     * Inherited
     */
    public function isInstalled($packageName)
    {
        $command = "port installed %s";
        exec(sprintf($command, $packageName), $output, $exitStatus);
        $nbFound = preg_match('/'.$packageName.'/', implode("\n", $output));

        return ($nbFound > 0);
    }

    /**
     * Inherited
     */
    public function getInstallCommand()
    {
        return 'port install';
    }
}
