<?php

class Database extends CommandBasis {

    private $database;
    private $project;

    public function __construct($project) {
        $this->project = $project;
        include("config/database.php");
        if(isset($database[$project]))
            $this->database = $database[$project];
    }

    public function compareToSave() {
        $files = array();
        if(!isset($this->database))
            return $files;
        foreach($this->database["folders"] as $folder) {
            $files = array_merge($files, glob($folder."/*.*"));
        }
        $save = file_get_contents("../project_manager/save/".$this->project.".txt");
        $saved = explode("\n", $save);
        return array_diff($files, $saved);
    }

    public function save($file) {
        $password = strlen($this->database["password"])==0?"":" --password=\"".$this->database["password"]."\"" ;
        $cmd = "mysql ".$this->database["dbname"]." -h ".$this->database["address"]." -u ".$this->database["user"]." $password < \"".$file."\"";
        return $this->run_default_command($cmd);
    }

    public function markAsSaved($file) {
        file_put_contents("../project_manager/save/".$this->project.".txt", "\n".$file, FILE_APPEND);
    }
   
}