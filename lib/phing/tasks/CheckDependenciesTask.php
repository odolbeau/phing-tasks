<?php

require_once "phing/Task.php";

class CheckDependenciesTask extends Task
{
    const PROPERTY_OSMANAGER = 'deps.manager';

    private $packageManager = array('gem', 'pip', 'pear');

    private $OSPackageManager = array('deb', 'rpm', 'macports');

    /**
     * The packages passed in the buildfile.
     */
    private $packages = null;

    /**
     * The package manager to use.
     */
    private $type = null;

    /**
     * The setter for the attribute "packages"
     */
    public function setPackages($packages)
    {
        $packages = explode(' ', $packages);
        //array_walk(&$packages, create_function('&$package', '$package = trim($package);'));
        $this->packages = $packages;
    }

    /**
     * The setter for the attribute "os"
     */
    public function setType($type)
    {
        if (!in_array($type, $this->packageManager) && !in_array($type, $this->OSPackageManager)) {
            throw new BuildException(sprintf('The type %s is not yet supported, please try an other', $type));
        }
        $this->type = $type;
    }

    /**
     * The init method: Do init steps.
     */
    public function init()
    {
        // nothing to do here
    }

    /**
     * The main entry point method.
     */
    public function main()
    {
        $OSManagerChoose = $this->project->getProperty(self::PROPERTY_OSMANAGER);

        if (is_null($OSManagerChoose) || !in_array($OSManagerChoose, $this->OSPackageManager)) {
            throw new BuildException('The property "'.self::PROPERTY_OSMANAGER.'" should be a valid OS package manager: "'.$OSManagerChoose.'" is not.');
        }

        // if current OS package manager is not defined as its we ignore it.
        if (in_array($this->type, $this->OSPackageManager) && $this->type != $OSManagerChoose) {
            return;
        }

        $pm = $this->buildPackageManager($this->type);
        $pkgToInstall = array();

        foreach($this->packages as $package) {
            $indents = str_repeat(" ", 30 - strlen($package));
            if (!$installed = $pm->isInstalled($package)) {
                $pkgToInstall[] = $package;
            }

            $this->log(
                sprintf('Checking for "%s" package [%s]%s[%s]', $this->type, $package, $indents, $installed ? "yes" : "no"),
                $installed ? Project::MSG_INFO : Project::MSG_ERR
            );
        }

        // Summarize all the packages to install
        if (!empty($pkgToInstall)) {
            $this->log(
                sprintf("-> Install missed packages with the following command: '%s %s'", $pm->getInstallCommand(), implode(' ', $pkgToInstall)),
                Project::MSG_INFO
            );
        }
    }

    private function buildPackageManager($type)
    {
        $managers = array(
            'deb' => 'DebPackageManager',
            'gem' => 'GemPackageManager',
            'macports' => 'MacPortsPackageManager',
            'pear' => 'PearPackageManager',
            'pip' => 'PipPackageManager'
        );


        $pm = new $managers[$type]();
        return $pm;
    }
}

?>
