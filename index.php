<?php
//*****************************************************************************
//
// NMT Cloudplayer  -  Version: 0.3 beta
// Script created by SImon Law (ramishi), inspired by iPhone File Browser for C200 by Strich9.
// html5 audio playback using AudioJS library - http://kolber.github.com/audiojs/
// UI Library by JQuery Mobile - http://jquerymobile.com/
//
// This is a beta release, Feel free to improve and modify according to your needs.
//
//*****************************************************************************


// The base path of the files you wish to browse
$_ENV['basepath'] = '/share/Music';
// File extension you want to hide from the list
$_ENV['hidden_files'] = array ("\\/", "php", "css", "htaccess", "sub", "srt", "idx", "smi", "js", "DS_Store", "jpg", "gif", "nmj", "jsp", "txt");
// File name of album conver art
$_ENV['coverart'] = 'cover.jpg';
// File name of album review text
$_ENV['review'] = 'review.txt';

//your server domain name, you can ignore this setting
$_ENV['domain'] =  'http://' . $_SERVER['HTTP_HOST'];

function loadreview($file) {
	$file = $file.'/'.$_ENV['review'];
	if (file_exists($file)) {
		$handle =fopen($file, "r");
		$contents = fread($handle, filesize($file));
		fclose($handle);
		echo htmlspecialchars($contents, ENT_QUOTES);
	} 
	/*else { 
		echo 'no review';
	}*/
	}

function loadart($file) {
	$file = $file.'/'.$_ENV['coverart'];
	if (file_exists($file)) {
		echo str_replace(" ", "%20",$file);
	} else {
		echo 'images/jewelcase_empty.png';
		}
	}



//check extension
function valid_extension($filename) {
		$ext = substr (strrchr ($filename, "."), 1);
		return array_search ($ext, $_ENV['hidden_files']);
	}
	
function format_size($size) {
      $sizes = array(" Bytes", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
      if ($size == 0) { return('n/a'); } else {
      return (round($size/pow(1024, ($i = floor(log($size, 1024)))), 2) . $sizes[$i]); }
}

function showContent($path){
   if ($handle = opendir($path)){      
       while (false !== ($file = readdir($handle)))
       {
		   if ($file=='.' || $file=='..' || valid_extension($file))
           {
			  // echo "hidden<br>";
			   } else {
				
				$fName =  htmlspecialchars($file, ENT_QUOTES);
				$file = $path."/".$file;			 
				$file =  htmlspecialchars($file, ENT_QUOTES);
				$fileurl = str_replace(" ", "%20",$file);
				
	
               if(is_file($file)) {
				    
                   echo "<li class='music' data-icon='false'><a class='musicfile' href='#' data-src='".$_ENV['domain'].$fileurl."'><h3>".$fName."</h3>"
                       ."<p class='size ui-li-aside'> ".format_size(filesize($file))."</p></a></li>";
               } elseif (is_dir($file)) {
                   echo "<li class='folder'><a href='".$_SERVER['SCRIPT_NAME']."?path=".$file."'>";
				   if (file_exists($file."/".$_ENV['coverart'])) {					   
						$folderart = $_ENV['domain'].$fileurl."/".$_ENV['coverart'];
						echo "<img src='$folderart'>";
					} else { 
						echo "<img src='images/jewelcase_empty.png'>";
					};
					echo "<h3>$fName</h3></a></li>";
               }
           }
       }

       closedir($handle);
   }	

}

function workaround_missing_functions() {
    if (!function_exists('mb_substr')) {
      function mb_substr($str, $start, $length = 1024, $encoding = false) {
        return substr($str, $start, $length);      
      }
   }

}
workaround_missing_functions();


?>

<!DOCTYPE HTML>
<html>
<head>
<title>NMT Cloudplayer</title>
<link rel="stylesheet" href="http://code.jquery.com/mobile/latest/jquery.mobile.min.css" />
<link href="style/style.css" rel="stylesheet" type="text/css">
<link href="style/audiojs.css" rel="stylesheet" type="text/css">
<!--change style by device-->
<link rel="stylesheet" media="all and (max-device-width: 480px)" href="style/iphone.css">
<link rel="stylesheet" media="all and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:portrait)" href="style/iphone.css">
<link rel="stylesheet" media="all and (min-device-width: 481px) and (max-device-width: 1024px) and (orientation:landscape)" href="style/ipad.css">
<link rel="stylesheet" media="all and (min-device-width: 1025px)" href="style/ipad.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js" type="text/javascript"></script>
<script src="http://code.jquery.com/mobile/latest/jquery.mobile.min.js" type="text/javascript"></script>
<script src="audiojs/audio.min.js" type="text/javascript"></script>
<script src="audiojs/player.js" type="text/javascript"></script>
<script>
 $(document).ready(function() {
  // disable ajax nav
  $.mobile.ajaxEnabled = false;
 });
</script>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no" />
</head>
<body>
<?php
setlocale(LC_ALL, 'en_US.UTF8');

if (isset($_GET['path'])){
	$actpath = $_GET['path'];
} else {
$actpath = $_ENV['basepath'];
}
?>
<div data-role="page">
  <div data-role="header"> 
    <!--Breadcrumb -->
    <div id="breadcrumb">
      <?php if ( $actpath != $_ENV['basepath']){
		$crumb = explode("/",str_replace($_ENV['basepath'],"",$actpath));
    	echo "<span><a href='" .$_SERVER['PHP_SELF']."?path=".$_ENV['basepath']."'>Home</a></span>";
    	$newpath = '';
    	foreach($crumb as $index => $value) {
        $newpath .= $value;
        // is not last item //
        if($index < count($crumb)-1)
            echo "<a href='" .$_SERVER['PHP_SELF']."?path=".$_ENV['basepath']. $newpath ."'>$value</a>";
        // it is last item //
        else
            echo $value;
        $newpath .= '/';
   		 }}
		?>
    </div>
    <!--Back button -->
    <?php 
			$up =  mb_substr($actpath, 0, (strrpos(dirname($actpath."/."),"/")));
			if ( $actpath != $_ENV['basepath']){
    		 echo "<a id='backbtn' href='".$_SERVER['PHP_SELF']."?path=".$up."' data-icon='back'>Back</a>";
	  		 } 
		 ?>
    <h1 id="headerh1">
      <?php  echo $value; ?>
    </h1>
  </div>
  <div data-role="content">
    <div id="infopanel">
      <div class="albumCover"> <span id="albumCover" class="albumCover coverMega"> <img class="art" src="<?php loadart($actpath); ?>" alt="<?php loadart($actpath); ?>" width="174px"/> <span class="jewelcase"></span> </span> </div>
      <div class="title">
        <?php
			$filename = explode("-",$value);
			$artist = $filename[0];
			$album = $filename[1];
      		echo "<h1> $album </h1><h2>$artist</h2>";
	  	?>
      </div>
      <div class="review">
        <?php loadreview($actpath); ?>
      </div>
    </div>
    <div class="content"> 
      <!--control -->
      
      <audio></audio>
      <div id='control'>
        <div class="audiojsZ">
          <div class="prev">
            <p class="prevZ"></p>
          </div>
          <div class="play-pauseZ">
            <p class="playZ"></p>
            <p class="pauseZ"></p>
            <p class="loadingZ"></p>
            <p class="errorZ"></p>
          </div>
          <div class="next">
            <p class="nextZ"></p>
          </div>
          <div class="volset">
            <div class="voldn">
              <p class="voldnZ"></p>
            </div>
            <div class="volup">
              <p class="volupZ"></p>
            </div>
            <div class="vbar">
              <div class="bar" ></div>
            </div>
          </div>
          <div class="scrubberZ">
            <div class="progressZ"></div>
            <div class="loadedZ"></div>
          </div>
          <div class="timeZ"> <em class="playedZ">00:00</em>/<strong class="durationZ">00:00</strong> </div>
          <div class="error-messageZ"></div>
        </div>
      </div>
      
      <!--Playlist -->
      <ul data-role="listview" data-filter="true" id="playlist">
        <?php showContent($actpath); ?>
      </ul>
    </div>
  </div>
  <div data-role="footer">
    <h4>NMT Cloudplayer  -  Version: 0.4 beta</h4>
  </div>
</div>
</body>
</html>