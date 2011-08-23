<?php

require_once "phing/Task.php";

class CheckDependenciesTask extends Task
{
  /**
   * The packages passed in the buildfile.
   */
  private $packages = null;

  /**
   * The os passed in the buildfile.
   */
  private $os = null;

  /**
   * The setter for the attribute "packages"
   */
  public function setPackages($packages)
  {
    $packages = explode(' ', $packages);
    array_walk(&$packages, create_function('&$package', '$package = trim($package);'));
    $this->packages = $packages;
  }

  /**
   * The setter for the attribute "os"
   */
  public function setOs($os)
  {
    $this->os = $os;
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
    // Only execute on selected OS
    if ($this->os == PHP_OS) {
      $this->_checkPackages();
    }
  }

  private function _checkPackages()
  {
    $checkPrompt = "Checking for package [%s]%s[%s]\n";
    // If on Mac use macports else use apt
    if ("Darwin" == $this->os) {
        $command = "port installed %s > /dev/null 2>&1";
    } else {
        $command = "dpkg --status %s > /dev/null 2>&1";
    }

    $missings = array();

    foreach ($this->packages as $pkg)
      {
        // Get display indentation spaces needed
        $indents = str_repeat(" ", 30 - strlen($pkg));
        // Match package in installed packages list, return true if installed
        exec(sprintf($command, $pkg), $output, $installed);
        if ($installed !== 0) {
          $missings[] = $pkg;
        }
        printf($checkPrompt, $pkg, $indents, $installed ? "no" : "yes");
      }

    if (!empty($missings)) {
      die("\n\nYou have missing dependencies. Following dependencies need to be installed:\n" . implode("\n", $missings) . "\n");
    }
  }
}

?>
