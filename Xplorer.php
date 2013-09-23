<html>
	<head>
		<title>PHP File Manager</title>
		<link href="css/bootstrap.css" rel="stylesheet" type="text/css" />
	</head>
	<body>
		<div class="container">
			<header>
				<nav class="navbar navbar-default navbar-static-top" role="navigation">
					<ul class="nav navbar-nav">
						<?php
							global $dir_path;
							global $entry;
							
							if (isset($_GET['directory'])) {
								$dir_path = $_GET["directory"];
							}
							else {
								$dir_path = $_SERVER["DOCUMENT_ROOT"]."/";
							}
							
							if (isset($_GET['function'])) {
								if($_GET['function'] == "mkdir") {
									if(isset($_GET['name'])) {
										mkdir($dir_path . "/" . $_GET['name'], 0700);
										echo $dir_path . "/" . $_GET['name'];
									}
									else{
										mkdir($dir_path . "New Folder");
									}
								}
								else{}
							}
							else {
							
							}	
							
							echo("<li><a href='?directory=" . str_replace(basename($dir_path), "", $dir_path) . "'><span class='glyphicon glyphicon-arrow-left'></span> Back</a></li>"); 
						?>
						<li><a href="#"><span class="glyphicon glyphicon-file"></span> New File</a></li>
						<li><a href="#"><span class="glyphicon glyphicon-folder-open"></span>  New Folder</a></li>
						<li><a href="#"><span class="glyphicon glyphicon-pencil"></span> Rename</a></li>
						<li><a href="#"><span class="glyphicon glyphicon-wrench"></span> Edit Permissions</a></li>
						<li><a href="#"><span class="glyphicon glyphicon-cloud-upload"></span> Upload New File</a></li>
					</ul>
				</nav>	
			</header>
			<table class="table table-bordered">
				<thead>
					<th>Type</th>
					<th>Name</th>
					<th>Size</th>
					<th>Permissions</th>
				</thead>
				<tbody>
				<?php
					function foldersize($dir){
						$count_size = 0;
						$count = 0;
						$dir_array = scandir($dir);
						foreach($dir_array as $key=>$filename){
							if($filename!=".." && $filename!="."){
								if(is_dir($dir."/".$filename)){
									$new_foldersize = foldersize($dir."/".$filename);
									$count_size = $count_size + $new_foldersize;
								}
								
								else if(is_file($dir."/".$filename)){
									$count_size = $count_size + filesize($dir."/".$filename);
									$count++;
								}
							}
						return $count_size;
						}
					}	
					
					$directories = scandir($dir_path);
					foreach($directories as $entry) {
						if (is_dir($dir_path . "/" . $entry) && !in_array($entry, array('.','..'))) {
							echo "<tr style='cursor: pointer;' onclick=\"window.location='?directory=". $dir_path . "/" . $entry ."'\"><td><center><span class='glyphicon glyphicon-folder-open'></span></center></td><td>" . $entry . "</td><td>" . foldersize($dir_path.$entry) . "KB</td><td>" . substr(sprintf('%o', fileperms($dir_path . "/" . $entry)), -4) . "</td></tr>";
						}
						else {
							if (is_file($dir_path . "/" . $entry) && !in_array($entry, array('.','..'))) {
								//set href to an id that will activate a jquery modal to edit the file.
								//add syntax highlighting for .php , .cpp, and .java files(they're most common)
								echo "<tr style='cursor: pointer;' onclick=\"window.location='?directory=". $dir_path . "/" . $entry ."'\"><td><center><span class='glyphicon glyphicon-file'></span></center></td><td>" . $entry . "</td><td>" . filesize($dir_path.$entry) / 1024 . "KB</td></tr>";
							}						
						}
					}
					/*
					Notes:
						- scandir has three parameters, dir, sort order, and context.
						- dir = directory  path
						- sort order = ascending by default(alphabetical)
						- context usually will not need to be used.
					*/
				?>
				</tbody>
			</table>
		</div>
	</body>
</html>