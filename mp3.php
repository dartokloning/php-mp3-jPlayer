<?php
/**
 * Created by Scott Fleming
 * MP3 Player using jPlayer and PHP
 */

include ("includes/CMP3File.php");
$files      = array_diff(scandir('mp3/'), array('..', '.'));
$a_list     = array();
$i          =0;
$head_js    = '<script type="text/javascript">
                //<![CDATA[
                $(document).ready(function(){
               ';
// Iterate through file array, and load our data package.

foreach($files as $filename){

    $filename="mp3/$filename"; // must include physical path to filename.
    $mp3file=new CMP3File;
    $mp3file->getid3($filename);

    $a_list[$i]['filename'] = $filename;
    $a_list[$i]['title']    = ucfirst(trim($mp3file->title));
    $a_list[$i]['artist']   = $mp3file->artist;
    $a_list[$i]['year']     = $mp3file->year;
    $a_list[$i]['filesize'] = FileSizeConvert(filesize(($filename))); // In case you want to display filesize

$head_js .= '

$("#jquery_jplayer_'. $i. '").jPlayer({
		ready: function () {
			$(this).jPlayer("setMedia", {
				title: "'. trim(ucfirst($mp3file->artist)). ' : '.$a_list[$i]['title'].'",
				mp3: "'.$filename.'"
			});
		},
		play: function() { // To avoid multiple jPlayers playing together.
			$(this).jPlayer("pauseOthers");
		},
		swfPath: "js/jquery.jplayer",
		supplied: "mp3",
		cssSelectorAncestor: "#jp_container_'.$i.'",
		wmode: "window",
		globalVolume: true,
		smoothPlayBar: true,
		keyEnabled: true
	});
';

$page .= "
<div class='text-center'>
<div id=\"jquery_jplayer_".$i. "\" class=\"jp-player\"></div>

 <div id=\"jp_container_".$i. "\" class=\"jp-audio\">
        <div class=\"jp-type-single\">
                <div class=\"jp-gui jp-interface\">
                    <ul class=\"jp-controls\">
                        <li><a href=\"javascript:;\" class=\"jp-play\" tabindex=\"1\">play</a></li>
                        <li><a href=\"javascript:;\" class=\"jp-pause\" tabindex=\"1\">pause</a></li>
                        <li><a href=\"javascript:;\" class=\"jp-stop\" tabindex=\"1\">stop</a></li>
                    </ul>

                    <div class=\"jp-progress\">
                        <div class=\"jp-seek-bar\">
                            <div class=\"jp-play-bar\"></div>
                        </div>
                    </div>
                    <div class=\"jp-details\">
                            <ul>
                                <li><span class=\"jp-title\"></span></li>
                            </ul>
                    </div>

                </div>
          </div>
    </div>

<div id=\"jplayer_inspector_". $i."\" class=\"jp-inspector\"></div>
</div>

";
    $i++;
}

$head_js .='
});
//]]>
</script>
';

/**
 * @param $bytes
 * @return string
 */
function FileSizeConvert($bytes)
{
    $bytes = floatval($bytes);
    $arBytes = array(
        0 => array(
            "UNIT" => "TB",
            "VALUE" => pow(1024, 4)
        ),
        1 => array(
            "UNIT" => "GB",
            "VALUE" => pow(1024, 3)
        ),
        2 => array(
            "UNIT" => "MB",
            "VALUE" => pow(1024, 2)
        ),
        3 => array(
            "UNIT" => "KB",
            "VALUE" => 1024
        ),
        4 => array(
            "UNIT" => "B",
            "VALUE" => 1
        ),
    );

    foreach($arBytes as $arItem)
    {
        if($bytes >= $arItem["VALUE"])
        {
            $result = $bytes / $arItem["VALUE"];
            $result = str_replace(".", "." , strval(round($result, 2)))." ".$arItem["UNIT"];
            break;
        }
    }
    return $result;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>PHP Mp3 Display with jPlayer</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script type="text/javascript" src="js/jPlayer/jquery.jplayer/jquery.jplayer.js"></script>
    <script type="text/javascript" src="js/jPlayer/jquery.jplayer/jquery.jplayer.inspector.js"></script>
    <link rel="stylesheet" type="text/css" href="js/jPlayer/skin/blue.monday/jplayer.blue.monday.css">
    <?php echo $head_js; ?>

</head>
<body>
<div class="container">
    <div>
       <?php echo $page; ?>
    </div>
</div>
</body>
</html>
