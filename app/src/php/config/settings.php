<?php
if (isset($_POST['button-id']) && isset($_POST['delicon'])) {
	require 'delete_icon.php';
}
if (isset($_POST['search_provider']) && isset($_POST['change_engine'])) {
    require 'change_engine.php';
}

$searchProviders = array(
    'Google' => 'https://www.google.com/search?q=',
    'Bing' => 'https://www.bing.com/search?q=',
    'Yahoo' => 'https://search.yahoo.com/search?p=',
    'DuckDuckGo' => 'https://duckduckgo.com/?q=',
    'Ask' => 'https://www.ask.com/web?q=',
    'Qwant' => 'https://www.qwant.com/?q=',
    'Startpage' => 'https://www.startpage.com/do/dsearch?query=',
    'Swisscows' => 'https://swisscows.com/web?query=',
    'OneSearch' => 'https://www.onesearch.com/search?q=',
    'Boardreader' => 'https://boardreader.com/s/'
);

function createSearchEngineSelect($searchProviders) {
    foreach ($searchProviders as $provider => $url) {
        echo '<option value="' . $provider . '">' . $provider . '</option>';
    }
}

?>

<div class="button-form">
	<h2>Upload New Icon</h2>
	<form id="uploadForm" method="POST" action="">
		<input type="text" id="icon-name" name="icon-name" placeholder="Icon Name" required><br>
		<input type="file" id="fileToUpload" name="icontoUpload" required><br>
		<input type="submit" name="submit" id="uploadBtn" value="Upload">
	</form>
</div>

<hr class="spacer-hr">

<div class="button-form">
	<h2>Delete An Icon</h2>
	<form method="POST" action="">
		<input type="hidden" name="delicon">
		<select name="button-id">
			<option disabled selected>--Select One--</option>
			<?php require 'uploaded_icon_list.php'; ?>
		</select><br>
		<input type="submit" name="submit" value="Delete">
	</form>
</div>

<hr class="spacer-hr">

<div class="button-form">
	<h2>Change Background</h2>
	<form id="BGuploadForm" method="POST" action="">
		<input type="file" id="BGToUpload" name="BGtoUpload" required><br>
		<input type="submit" name="submit" id="BGuploadBtn" value="Change">
	</form>
</div>

<hr class="spacer-hr">

<div class="button-form">
    <h2>Change Search Engine</h2>
    <form method="POST" action="">
        <input type="hidden" name="change_engine">
        <select name="search_provider">
            <option value="turned_off">Turn Off Search Bar</option>
            <?php createSearchEngineSelect($searchProviders); ?>
        </select><br>
        <input type="submit" name="submit" id="BGuploadBtn" value="Change">
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('#uploadForm').submit(function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

        var fileInput = document.getElementById('fileToUpload');
        var file_data = $('#fileToUpload').prop('files')[0];
        var file_ext = file_data.name.split('.').pop().toLowerCase();
        var wl_ext = ['jpg', 'jpeg', 'png', 'gif']; // Whitelist of image extensions

        if (wl_ext.indexOf(file_ext) === -1) {
            alert('Only image files (JPG, JPEG, PNG, GIF) are allowed!');
            return;
        }

        const timestamp = Date.now();
        const newName = `file_${timestamp}.${file_ext}`;
        const newFile = new File([file_data], newName, { type: file_data.type });
        fileInput.files[0] = newFile;

        var form_data = new FormData(this);

        $.ajax({
            url: '/src/php/config/icon_upload_handler.php', // Replace with the appropriate server-side file handler
            type: 'post',
            data: form_data,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#uploadBtn').val(response);
                $('#fileToUpload').val('');
                $('#icon-name').val('');
				setTimeout(function() {
					location.reload();
				}, 2000);

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError);
            }
        });
    });
});

$(document).ready(function() {
    $('#BGuploadForm').submit(function(e) {
        e.preventDefault(); // Prevent the form from submitting normally

        var fileInput = document.getElementById('BGToUpload');
        var file_data = $('#BGToUpload').prop('files')[0];
        var file_ext = file_data.name.split('.').pop().toLowerCase();
        var wl_ext = ['jpg', 'jpeg', 'png', 'gif']; // Whitelist of image extensions

        if (wl_ext.indexOf(file_ext) === -1) {
            alert('Only image files (JPG, JPEG, PNG, GIF) are allowed!');
            return;
        }

        const timestamp = Date.now();
        const newName = `file_${timestamp}.${file_ext}`;
        const newFile = new File([file_data], newName, { type: file_data.type });
        fileInput.files[0] = newFile;

        var form_data = new FormData(this);

        $.ajax({
            url: '/src/php/config/background_upload_handler.php', // Replace with the appropriate server-side file handler
            type: 'post',
            data: form_data,
            contentType: false,
            processData: false,
            success: function(response) {
                $('#BGuploadBtn').val(response);
                $('#BGToUpload').val('');
				setTimeout(function() {
					location.reload();
				}, 2000);

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError);
            }
        });
    });
});
</script>
