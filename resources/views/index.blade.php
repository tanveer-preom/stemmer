<!DOCTYPE html>
<html lang="en">
	<head>
		<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
		<title>Index Of</title>
		<style>
			a {

				
				vertical-align: center;
			}
			img
			{
				vertical-align: center;	
			}
			div {
    			border: 1px solid powderblue;
			}


		</style>

	</head>
	<body>
		<h2>Mobile Server Directories</h2>
		<div class="file_button_container"><input type="file" /></div>
		<div>
			<?php

				if($directory!= 'home/tanveer')
				{
					$strParts =explode('/', $directory);
					$path = $strParts[0];
					for($count=1;$count<count($strParts)-1;$count++)
					{
						$path=$path.'/'.$strParts[$count];
					}
					echo '<i class="fa fa-reply-all" aria-hidden="true"></i>&nbsp;<a href="/index/'.$path.'">'.'Up'.'/</a><br>';

				} 
			 	$files = scandir('/'.$directory);
			  	$count=0;
			  	$downloadables = [];
			 	foreach ($files as $folder) {
			 	# code...
			 		if($count<2)
			 		{
			 			$count++;
			 			continue;
			 		}
			 		$path = $directory.'/'.$folder;

			 		if(is_dir('/'.$path))
			 			echo '<i class="fa fa-folder" aria-hidden="true"></i>&nbsp;<a href="/index/'.$path.'">'.$folder.'/</a><br>';
			 		else
			 			$downloadables[] = $folder;	
				 }
				 $count=0;
				 foreach ($downloadables as $file) {
				 	# code...
				 	if($count<2)
			 		{
			 			$count++;
			 			continue;
			 		}
			 		$path = $directory.'/'.$file;
				 	echo '<i class="fa fa-file-text" aria-hidden="true"></i>&nbsp;<a href="/download/'.$path.'">'.$file.'/</a><br>';
				 }


			
			?>
		</div>

	</body>	



</html>