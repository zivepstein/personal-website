<?php
   session_start();
   if(strlen($_GET['schema']) ==0 ){
        $header = "Location: https://yalepsych.qualtrics.com/SE/?SID=SV_0I2f7pTapFcfFC5&browser=" . $_SESSION['browser'] . "&idnum=" . $_SESSION['idnum'] . "&schema=" . $_SESSION['schema'] . "&output=" . $_SESSION['output'];
        session_unset();
        session_destroy();
        header($header);
        exit();
    }
   if (!isset($_POST['result'])) {
   //only run once
       $_SESSION['output'] = "";
       $_SESSION['browser'] =  urlencode($_SERVER['HTTP_USER_AGENT'] . "\n\n");
       $_SESSION['idnum'] = $_GET['idnum'];
       $_SESSION['schema'] = $_GET['schema'];
       $_SESSION['cheater'] = 0;
    }
   //dyanmic for each page
   $schema = $_GET['schema'];
   $task = $schema{0};
   $partner = $schema{1};
   $partnerType = $schema{2};
   $trueFalse = $schema{3};
   $orient = mt_rand(0, 1);
   $timer = '21000';
   $noiseTest = '0';
    if ($orient ==1 ){
         $left = "tasksFinal/". $schema{0} . $schema{3}. ".mp4" ;
         $right ="partnersFinal/" . $schema{1} . $schema{2} . ".mp4";
      //  $left = "tasks/". "B" . "T". ".mp4" ;
        //$right ="partners/" . "c" . "1" . "mp4";
   } else {
       $right = "tasksFinal/". $schema{0} . $schema{3}. ".mp4" ;
        $left ="partnersFinal/" . $schema{1} . $schema{2} . ".mp4";
    //$left = "tasks/". "B" . "T". ".mp4" ;
      //  $right ="partners/" . "c" . "1" . "mp4";
   }
   if ($task == 'x'){
    	$timer = '4000';
    	$noiseTest = '1';
    	$encoded = (10*$schema{1}) + $schema{2};
    	$decoded = $encoded % 8;
    	$right = "noiseTest/n". $decoded . ".mp4" ;
    }
    ?>

<html>
    <head>
        <link rel="stylesheet" type="text/css" href="mystyle.css">
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
	    <script>
      function disableF5(e) { if (e.which == 116) e.preventDefault(); };
     $(document).bind("keydown", disableF5);
		    function showDiv(){
		       var obj = document.getElementById('survey');
		       obj.style.display = 'block';

		  }
		  	window.history.forward();
			function noBack() { window.history.forward(); }
      		function playVids(){
           var v1 = document.getElementsByTagName("video")[0];
           var v2 = document.getElementsByTagName("video")[1];
           v1.play();
           v2.play();
      }
            $(function() {
            $('input[name="result"]').change(function(){
                $('#survey').submit();
            });
        });
	   </script>
</head>

<body onLoad="setTimeout('showDiv()', <?php echo $timer; ?>); setTimeout('playVids()',2000);noBack();" onpageshow="if (event.persisted){ noBack();} " onunload = "<?php $_SESSION['cheater'] = 1;?>;">
<?
if (isset($_POST['result'])) {
    // at least second interation
    // check if $schema length is 2 => redirect header(..)

    	function decodeAndHash($tens, $ones){
    		$encoded = (10*$tens) + $ones;
    		$decoded = $encoded % 8;
    		#hash function below
			if ($decoded ==0){
				return '10';
			} else if ($decoded == 1 || $decoded == 2 || $decoded == 3 || $decoded == 4){
				return (string)$decoded;
			} else{
				return (string) ($decoded +2);
			}
		}
        $localSchema = $_POST['schema'];
        $response = $_POST['result'];
        if ($localSchema{0} == 'x'){
          $output = "n";
          if ($response == decodeAndHash($localSchema{1},$localSchema{2})){
            $output = "y";
          }
        } else {
        $output = "0";

        if($response == $localSchema{3}){
            $output ="1";
        } else if($response =="IDK"){
          $output = "2";
        }
      }
        $_SESSION['output'].=$output;
    // else save $_POST['schema'] associated w form data as session var
}
?>
    
    		
           <? if($noiseTest =='0'){?>

           <div id="firstDiv" style="text-align: center; margin-top: 0">

        <div class="aParent" style ="float: left; clear: both; margin-left: 220; margin-right: 100; ">
            <div class="content">
                <video id = "left" width="440" height="500" > //EDIT: loop="true" is deprecated nowadays
                    <source src="http://hclonlineresearchstudies.com/ze/<?php echo $left; ?>" type="video/mp4" />
                    Your browser does not support the video tag.
                </video> 
            </div>

            <div class="content">
                <video id = "right" width="440" height="500"> //EDIT: loop="true" is deprecated nowadays
                    <source src="http://hclonlineresearchstudies.com/ze/<?php echo $right; ?>" type="video/mp4" />
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>

    <div id="secondDiv" style="clear: both; margin: -600 400; margin-bottom: -20px;">
        <form method="post" action="emplode.php?schema=<?=substr($schema, 4) ?>" id='survey' style="display: none;">
           Is the red arrow pointing to the correct dot?<br>
            <input type="hidden" name="schema" value="<?=substr($schema, 0,4)?>">
            <div style ="margin: 10 0;">
          <input type="radio" name="result" value="T" >Yes<br>
            </div>
            <div style ="margin: 5 0;">
          <input type="radio" name="result" value="F">No <br>
            </div>
            <div style ="margin: 5 0;">
          <input type="radio" name="result" value="IDK">I don't know <br>
            </div>
            <? } else{?>

            <div id="firstDiv" style="text-align: center; margin-top: 0">

        <div class="aParent" style ="float: left; clear: both; margin-left: 370; margin-right: 100; ">

            <div class="content">
                <video id = "right" width="440" height="500"> //EDIT: loop="true" is deprecated nowadays
                    <source src="http://hclonlineresearchstudies.com/ze/<?php echo $right; ?>" type="video/mp4" />
                    Your browser does not support the video tag.
                </video>
            </div>
        </div>
    </div>

    <div id="secondDiv" style="clear: both; margin: -600 400; margin-bottom: -20px;">
        <form method="post" action="emplode.php?schema=<?=substr($schema, 4) ?>" id='survey' style="display: none;">
            What number did you hear? Please type the digits then press enter. <br>
             <input type="hidden" name="schema" value="<?=substr($schema, 0,4)?>">
            <div style ="margin: 10 0;">
          <input type="text" name="result" value="" ><br>
          
            </div>
            <? }
              ?>
		</form>
    </div>

</body>
</html>