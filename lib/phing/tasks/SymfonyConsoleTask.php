<?php

require_once "phing/Task.php";

class SymfonyConsoleTask extends Task
{
  /**
   * The command passed in the buildfile.
   */
  private $command = null;

  /**
   * The console dir location.
   */
  private $dir = 'app/console';

  /**
   * The force parameter passed in the buildfile.
   */
  private $force = false;

  /**
   * the setter for the attribute "command"
   */
  public function setcommand($command)
  {
    $this->command = $command;
  }

  /**
   * the setter for the attribute "dir"
   */
  public function setdir($dir)
  {
    $this->dir = $dir;
  }

  /**
   * The setter for the attribute "force"
   */
  public function setforce($force)
  {
    $this->force = $force;
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
    $this->_execCommand();
  }

  private function _execCommand()
  {
    $output = array();
    exec("php $this->dir/console $this->command " . (true === $this->force ? "--force" : ""), $output);
    echo implode("\n", $output);
  }
}

?>
