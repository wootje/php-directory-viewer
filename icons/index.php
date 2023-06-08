<!DOCTYPE html>
<html>
<head>
	<style>
		div {
			min-width: 10px;
			display: inline-block;
			padding-left: 10px;
		}
css
Copy code
	.bold {
		font-weight: 700;
	}
</style>
</head>
<body>
	<div></div>
	<div class="bold">This page generates a base64 encoded string for all image files in this directory.</div>
php
Copy code
<br><br>

<div></div>
<div class="bold">First line is the name of the file.</div>

<div></div>
<div class="bold">Second line is the encoded base64 string.</div>

<br><br><br>

<?php
	$directory = getcwd();
	$extension = array('jpg', 'png', 'gif');
	$ignorearray = array('index.php');
	$files = glob($directory . '/*.*');

	foreach ($files as $file) {
		$fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
		if (in_array($fileExtension, $extension) && !in_array(basename($file), $ignorearray)) {
			$imagedata = file_get_contents($file);
			$base64 = base64_encode($imagedata);
			?>
			<br>
			<img src="<?php echo basename($file); ?>" alt="<?php echo $directory . '/' . basename($file); ?>" height="75" width="75" style="margin-left:1em;">
			<br><br>
			<?php
			$realDocRoot = $_SERVER['DOCUMENT_ROOT'];
			$realDirPath = __DIR__;
			$suffix = str_replace($realDocRoot, '', $realDirPath);
			$prefix = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
			$folderUrl = $prefix . $_SERVER['HTTP_HOST'] . $suffix;
			?>
			<div style="font-weight: 700;"><?php echo basename($file); ?></div>
			<br><br>
			<div></div>
			<div><?php echo $base64; ?></div>
			<br><br><br>
			<?php
		}
	}
?>

<br><br> 
.
</body>
</html>