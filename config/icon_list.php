<option value="">--No Icon--</option>

<?php
$connection = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$connection) {
	die('Connection failed: ' . mysqli_connect_error());
}

$tableName = 'icons';
$checkTableQuery = "SHOW TABLES LIKE '$tableName'";
$tableExists = mysqli_query($connection, $checkTableQuery);

if (mysqli_num_rows($tableExists) == 0) {
	$createTableQuery = "CREATE TABLE $tableName (
		id INT(11) AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(255) NOT NULL,
		link VARCHAR(255) NOT NULL
	)";

	if (!mysqli_query($connection, $createTableQuery)) {
		mysqli_close($connection);
		exit();
	}
}

$query = "SELECT link, name FROM $tableName";
$result = mysqli_query($connection, $query);

if (mysqli_num_rows($result) > 0) {
	while ($row = mysqli_fetch_assoc($result)) {

		$link = $row['link'];
		$name = $row['name'];

		echo "<option value='".$link."'>".$name."</option>";
	}
}

mysqli_close($connection);

?>

<option value="src/images/cyberpanel.png">CyberPanel</option>
<option value="src/images/jellyfin.png">JellyFin</option>
<option value="src/images/linode.png">Linode</option>
<option value="src/images/mineos.png">MineOS</option>
<option value="src/images/netgear.png">NetGear</option>
<option value="src/images/openlitespeed.png">OpenLiteSeed</option>
<option value="src/images/pfsense.png">pfSense</option>
<option value="src/images/pihole.png">PiHole</option>
<option value="src/images/plex.png">Plex</option>
<option value="src/images/portainer.png">Portainer</option>
<option value="src/images/proxmox.png">Proxmox</option>
<option value="src/images/sophos.png">Sophos</option>
<option value="src/images/truenas.png">TrueNAS</option>
<option value="src/images/uptimekuma.png">UpTime Kuma</option>
<option value="src/images/yacht.png">Yacht</option>
