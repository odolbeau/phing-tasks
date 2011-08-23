<?php

require_once "phing/Task.php";

class SymfonyConsoleTask extends Task
{
  /**
   * The command passed in the buildfile.
   */
  private $command = null;

  /**
   * The force parameter passed in the buildfile.
   */
  private $force = false;

  /**
   * The setter for the attribute "command"
   */
  public function setCommand($command)
  {
    $this->command = $command;
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
    exec("php app/console $this->command " . (true === $this->force ? "--force" : ""), $output);
    echo implode("\n", $output);
  }
}

?>
