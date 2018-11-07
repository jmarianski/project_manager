<?php
	if($_GET["project"]==null) {
		$projects = $git->fetchProjects();
		foreach($projects as $p) { ?>
		<a href='index.php?project=<?=$p?>'><?=$p?></a><BR>
		<?php }
	} else {
		chdir("../".$_GET["project"]);
		if($_GET["checkout"]!=null) {
			echo "Checkout: ".$_GET["checkout"]."<BR>\n";
			foreach($git->checkoutRemote($_GET["checkout"]) as $line) {
				echo $line."<BR>\n";
			}
		}
		if($_GET["save"]!=null) {
			echo "Zapisuje: ".$_GET["save"]."<BR>\n";
			echo implode("<BR>", $db->save($_GET["save"]))."<BR>";
			$db->markAsSaved($_GET["save"]);
		}
		if($_GET["mark"]!=null) {
			echo "Oznaczam: ".$_GET["mark"]."<BR>\n";
			$db->markAsSaved($_GET["mark"]);
		}
		echo "<h1>".$_GET["project"]."</h1>";
		echo "<a href=\"?project=".$_GET["project"]."\">Odśwież</a><BR>";
		echo "<a href=\"?content=".$_GET["project"].".adframe.no\">Przejdź do projektu</a><BR>";
		passthru("git fetch --all"); 
		echo "<BR>";
		echo implode("<BR>", $git->run_default_command("git version"))."<BR>"; 
		echo $git->status();
		$remotes = $git->fetchRemotes();
		$db_changes = $db->compareToSave();
		if(!empty($db_changes)) {
			echo "<BR>Zmiany do bazy danych:\n<table>\n";
			foreach($db_changes as $one) { ?>
			<tr>
				<td><?=$one?></td>
				<td><a href="index.php?project=<?=$p?>&save=<?=urlencode($one)?>">Zapisz</a></td>
				<td><a href="index.php?project=<?=$p?>&mark=<?=urlencode($one)?>">Oznacz jako zapisane</a></td>
			</tr>
			<?php }
			echo "</table>\n";
		}
		echo "<table>";
		foreach($remotes as $r) {
			?>
		<tr><td><a href='index.php?project=<?=$p?>&checkout=<?=urlencode($r[0])?>'><?=$r[0]?></a></td><td><?=$r[1]?></td><td><?=$r[2]?></td><td><?=$r[3]?></td></tr>
		<?php }
		echo "</table>";
	}
?>
<pre>
<?php
echo shell_exec("tail /var/log/httpd/domains/adframe.no.error.log");

?>