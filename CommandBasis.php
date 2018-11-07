<?php

class CommandBasis {

    public function run_default_command($cmd) {
        // echo "$cmd\n";
        exec("$cmd 2>&1", $output, $status);
        return $output;
    }

}