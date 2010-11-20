<?php

require_once "phing/Task.php";

class MyRtexecTask extends Task {

    /**
     * The dir passed in the buildfile.
     */
    private $dir = null;
    
    /**
     * The message passed in the buildfile.
     */
    private $command = null;

    /**
     * The setter for the attribute "message"
     */
    public function setCommand($str) {
        $this->command = $str;
    }

    /**
     * Specify the working directory for executing this command.
     * @param PhingFile $dir
     */
    function setDir(PhingFile $dir) {
        $this->dir = $dir;
    }

    /**
     * The init method: Do init steps.
     */
    public function init() {
        // nothing to do here
    }

    /**
     * The main entry point method.
     */
    public function main() {

        if ($this->dir !== null) {
            if ($this->dir->isDirectory()) {
                $currdir = getcwd();
                @chdir($this->dir->getPath());
            } else {
                throw new BuildException("Can't chdir to:" . $this->dir->__toString());
            }
        }

        $a = popen($this->command, 'r');

        while($b = fgets($a)) {
            $this->log($b);
            flush();
        }
        pclose($a);

        if ($this->dir !== null) {
            @chdir($currdir);
        }

    }

}

?>

