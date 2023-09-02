<?php

if (isset($_POST['input'])) {
    // Read the commit log from the input
    $commitLog = $_POST['input'];

    // Split the commit log into individual lines
    $lines = explode("\n", $commitLog);

    // Format the commit log for display
    $formattedCommitLog = "";
    foreach ($lines as $line) {
        // Trim the line to remove leading/trailing spaces
        $line = trim($line);

        // Skip empty lines or lines that don't match the expected format
        if (empty($line) || !preg_match('/^([^,]+), ([^,]+), ([^,]+), (.+)$/', $line, $matches)) {
            continue;
        }

        // Extract the components from the matched groups
        $usr = $matches[1];
        $datetime = $matches[2];
        $commitName = $matches[3];
        $description = $matches[4];

        // Remove surrounding double quotes from the description
        $description = trim($description, '"');

        // Format the datetime string
        $formattedDatetime = date("l, F jS, Y", strtotime($datetime));

        // Build the formatted commit log
        $formattedCommitLog .= "User: $usr\n";
        $formattedCommitLog .= "Date: $formattedDatetime\n";
        $formattedCommitLog .= "Commit Name: $commitName\n";
        $formattedCommitLog .= "Description: $description\n\n";
    }

    // Generate a unique filename
    $filename = "formatted_commit_log_" . date("YmdHis") . ".txt";

    // Write the formatted commit log to a file
    file_put_contents($filename, $formattedCommitLog);

    // Set headers for file download
    header("Content-Type: text/plain");
    header("Content-Disposition: attachment; filename=\"$filename\"");
    header("Content-Length: " . filesize($filename));

    // Output the file contents
    readfile($filename);

    // Delete the temporary file
    unlink($filename);

    // Terminate the script
    exit();
} else {
    ?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Git File Formatter</title>
</head>
<body>

<form method="POST" action="">
    <textarea name="input" rows="20" cols="80" style="width: 80%;"></textarea><br>
    <input type="submit" name="submit" value="Format">
</form>

</body>
</html>

<?php } ?>
