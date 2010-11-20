<?php

require_once "phing/Task.php";

class MyDependTask extends Task {

    /**
     * The dir passed in the buildfile.
     */
    private $names = null;
    
    /**
     * The message passed in the buildfile.
     */
    private $type = null;

    /**
     * The setter for the attribute "message"
     */
    public function setNames($names) {
        $names = explode(',', $names);
        array_walk(&$names, 'trim');
        $this->names = $names;
    }

    /**
     * Specify the working directory for executing this command.
     * @param PhingFile $dir
     */
    function setType($type) {
        $this->type = $type;
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
        if ($this->type == 'phpext')
            $this->_checkPhpExt();
        else
            $this->_checkCmd();
    }

    private function _checkPhpExt() {
        $line = "[%s] PHP EXTENSION \t: `%s`";
        $content = array();
        exec('php -m', &$content);
        $content = implode("\n", $content);
        foreach($this->names as $ext) {
            if (preg_match('/'.$ext.'/', $content) > 0)
                $this->log(sprintf($line, 'OK', $ext));
            else
                $this->log(sprintf($line, 'KO', $ext), Project::MSG_ERR);
        }
    }

    private function _checkCmd() {
        $line = "[%s] COMMAND \t: `%s`";
        foreach($this->names as $bin) {
            exec('which ' . $bin, &$content, &$return);
            if ($return === 0)
                $this->log(sprintf($line, 'OK', $bin));
            else
                $this->log(sprintf($line, 'KO', $bin), Project::MSG_ERR);
        }
    }

}

?>

