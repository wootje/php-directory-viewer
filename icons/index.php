<html>
<br>
<span style="font-weight:700;"> This page generates a base64 encoded string for all image files in this directory. </span>
<br>
<br>
<span style="font-weight:700;"> Fist line is the name of the file. </span>
<br>
<span style="font-weight:700;"> Second line is the encoded base64 string. </span>
<br>
<br>
<br>
<?php
	$ignorearray = array('index.php');
	$files = glob('folder/*.{jpg,png,gif}', GLOB_BRACE);
	foreach ( glob($directory . '*' . $extension) as $file) {
		if(in_array(($file),$ignorearray)) {
			echo '';
		} else {
			$imagedata = file_get_contents($file);
			$base64 = base64_encode($imagedata); ?>  
			<br> 
			<img src="<?php print$file; ?>" alt="<?php print$file; ?>" height="75" width="75"></img>
			<br>
			<br>
			<span style="font-weight:700;"><?php print$file . ':'; ?></span>
			<br> 
			<?php print$base64; ?> 
			<br>
			<br>
			<?php
		} 
	} 
?>
<br>
<br> 
.
</html>