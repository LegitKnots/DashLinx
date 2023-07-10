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

<option value="src/images/icons/cyberpanel.png">CyberPanel</option>
<option value="src/images/icons/jellyfin.png">JellyFin</option>
<option value="src/images/icons/linode.png">Linode</option>
<option value="src/images/icons/mineos.png">MineOS</option>
<option value="src/images/icons/netgear.png">NetGear</option>
<option value="src/images/icons/openlitespeed.png">OpenLiteSeed</option>
<option value="src/images/icons/pfsense.png">pfSense</option>
<option value="src/images/icons/pihole.png">PiHole</option>
<option value="src/images/icons/plex.png">Plex</option>
<option value="src/images/icons/portainer.png">Portainer</option>
<option value="src/images/icons/proxmox.png">Proxmox</option>
<option value="src/images/icons/sophos.png">Sophos</option>
<option value="src/images/icons/truenas.png">TrueNAS</option>
<option value="src/images/icons/uptimekuma.png">UpTime Kuma</option>
<option value="src/images/icons/yacht.png">Yacht</option>
