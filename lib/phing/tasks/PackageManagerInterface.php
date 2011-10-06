<?php

interface PackageManagerInterface
{
    /**
     *
     * @param String $packageName Name of the package to check
     * @return Boolean True if installed, False if is not.
     */
    public function isInstalled($packageName);

    /**
     * @return String The command to execute to install packages
     */
    public function getInstallCommand();
}
