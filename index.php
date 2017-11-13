<?php
function dirToArray($dir, $path) {
    $result = array();
    $cdir = scandir($dir);
    foreach ($cdir as $key => $value) {
        if (!in_array($value, array(".", ".."))) {
            if (is_dir($dir . DIRECTORY_SEPARATOR . $value)) {
                $path .= "/$value/";
                $result[] = dirToArray($dir . DIRECTORY_SEPARATOR . $value, $path);
                $path = '';
            } else {
                if (stristr($value, '.wsdl') !== false) {
                    $filename = str_replace('.wsdl', '', $value);
                    $result[$filename] = '/Application/Model/Tmservice' . $path . $value;
                }
            }
        }
    }

    return $result;
}

$files = [];
$wsdlFiles = dirToArray(realpath('./../Application/Model/Tmservice'), '');

foreach ($wsdlFiles as $filePaths) {
    if (is_array($filePaths)) {
        foreach($filePaths as $key => $file) {
            if (is_array($file)) {
                $files = array_merge($file, $files);
            } else {
                $files[$key] = $file;
            }
        }
    } else {
        $filename = pathinfo($filePaths, PATHINFO_FILENAME);
        $files[$filename] = $filePaths;
    }
}
ksort($files);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title id="wsdl-name-title">Documentation</title>
    <link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Open+Sans:400italic,400,600|Open+Sans+Condensed:300" />
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/normalize/3.0.0/normalize.min.css" />
    <link rel="stylesheet" type="text/css" href="styles.css" />

    <script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.0/jquery.min.js"></script>
    <script	src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
    <script src="Saxon-CE_1.1/Saxonce/Saxonce.nocache.js"></script>
    <script src="index.js"></script>
</head>
<body>
	<header>
	    <!--// <div id="load_spinner"></div> //-->
		<h1 id="wsdl-name-header" class="text-white">WSDL Viewer</h1>
		<div class="action_bar">
			<form method="GET">
				<p>
					<span>
					    <input type="text" name="wsdl" id="wsdl" size="50" placeholder="Absolute or relative URI to WSDL" />
					</span>
					<span class="text-white"> - OR - </span>
					<span>
					    <select id="available_wsdls">
							<option>-- SELECT --</option>
            		 	<?php foreach ($files as $key => $value) { ?>
            		 	    <option value="<?php print $value ?>"><?php print $key ?></option>
            		 	<?php } ?>
        			    </select>
					</span>
				</p>

				<div id="home_btn">
        			<button type="button">home</button>
        		</div>
        		<div id="submit_btn">
    			    <button type="submit">go</button>
    			</div>
			</form>
		</div>
	</header>

    <div id="container">
        <div id="output">
        <?php include 'index.html'; ?>
        </div>
    </div>

	<footer>
		<a href="#top"><img id="top-image" src="up.svg" height="24" /></a>
	</footer>

	<script type="text/javascript">
	    $('#available_wsdls').on('change', function() {
            $('#wsdl').val($(this).val());
	    });
	    $('#home_btn button').on('click', function() {
		    location.href = '//' + location.hostname + '/wsdl/';
	    });
	</script>
</body>
</html>