<?php

class Git extends CommandBasis {
    
	public function fetchProjects() {
		chdir("../");
		$dirs = array();
		$files = glob("*");
		foreach($files as $f)
			if(is_dir($f) && is_dir($f."/.git/"))
				$dirs[] = $f;
			return $dirs;
	}

	public function fetchRemotes() {
		$output = $this->run_default_command("git for-each-ref --format=\"%(refname:short)///%(authorname)///%(creatordate:short)///%(contents:subject)\" --sort=-committerdate");
		$pairs = array();
		foreach($output as $line) {
			if(strlen($line)==0)
				continue;
			$explode = explode('///', $line);
			if(substr($explode[0], 0, 1)!="(")
				$pairs[] = $explode;
		}
		return $pairs;


	}

	public function status() {
		return implode("<BR>", $this->run_default_command("git status"));
	}

	public function checkoutRemote($remote) {
		return $this->run_default_command(mb_convert_encoding("git checkout $remote", 'auto', 'auto'));
	}

	public function getCurrentRemote() {
		$output = $this->run_default_command("git rev-parse --abbrev-ref HEAD");
		return $output[0];
	}

}



/* 
<pre>
<?php
$LOCAL_ROOTS = array("/var/www/treetank", "/var/www/gmaps");
foreach($LOCAL_ROOTS as $LOCAL_ROOT) {
        $cmd = "cd $LOCAL_ROOT && git pull";
        echo "$cmd\n";
        exec("$cmd 2>&1", $output, $status);
}
foreach($output as $line)
        echo $line."\n";
?>
</pre>
*/