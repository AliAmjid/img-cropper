<?php
if(isset($_POST['test'])) {
	$data = $_POST['test'];
	if(empty($data)) {
		echo 'Please crop image 1st';
		exit();
	}
	header('Content-type:image/png');
	echo base64_decode($data);
	exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Title</title>
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.2/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
</head>
<body>
<br>
<form action="example.php" method="post">
		<input type="text" name="test" id="btn-2" class="image-control sr-only"
				 data-label="Test 1"
				 data-thumbnail="https://avatars0.githubusercontent.com/u/3456749?s=160"
				 data-width="500"
				 data-height="500"
				 data-thumbnail-ratio="0.5"
				 data-scale-x="1"
				 data-scale-y="1"
				 accept="image/*">
	<button>Submit</button>
</form>
</body>
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.2/js/bootstrap.bundle.min.js"></script>
<script src="ImageControl.all.min.js"></script>
</html>