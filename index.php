<?php

   require 'inc/session.php';

   $satisFile = file_get_contents('http://list.satis.shopgo.io/');
   $satisList = json_decode($satisFile,true);
   $satisList = $satisList["packages"];

   $compLFile = file_get_contents($_SERVER['DOCUMENT_ROOT']."/composer.json");
   $compList = json_decode($compLFile, true);
   $compList =$compList["require"];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>Extensions Manager</title>
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script>
$(document).ready(function(){
$(".install").click(function(){

    $("#result").empty();
    var extName=($(this).val()).split(":")[0];
    var extVersion =document.getElementById(extName).value;
    document.getElementById('loading').style.display = 'block';

$.ajax({url:"action.php",type : "GET",
        data: { ext: extName, version: extVersion, action:"install" },
        dataType: 'json',
        success:function(result)
        {
            $("#result").empty();
            $('#loading').hide();
            document.getElementById('loading').style.display = 'none';
            $("#result").html(result[0]);

            if(result[1] != "null") {
                $("#extCount").hide(300);
                $("#extCount").html("&nbsp;".concat(result[1]));
                document.getElementById("extCount").style.color = "yellow";
                $("#extCount").show(800);

                var ibc = document.getElementsByClassName("install");
                var i;
                for (i = 0; i < ibc.length; i++) {
                    if (ibc[i].value.split(":")[0] == extName) {
                        ibc[i].disabled = true;
                    }
                }

                var dbc = document.getElementsByClassName("delete");
                var j;
                for (j = 0; j < dbc.length; j++) {
                    if(ibc[j].value.split(":")[0] == extName) {
                        dbc[j].disabled=false;
                    }
                }
            }
        },
        error: function()
        {
            alert('error!');
        }
        });
}),
$(".delete").click(function(){
    $("#result").empty();
    var extName=($(this).val()).split(":")[0];
    document.getElementById('loading').style.display = 'block';

    $.ajax({url:"action.php",type : "GET",
        data: { ext: extName, action:"delete" },
        dataType: 'json',
        success:function(result)
        {
            $('#loading').hide();
            $("#result").empty();       
            $("#result").html(result);
            document.getElementById('loading').style.display = 'none';
            $("#result").html(result[0]);

            if(result[1] != "null") {
                $("#extCount").hide(300);
                $("#extCount").html("&nbsp;".concat(result[1]));
                document.getElementById("extCount").style.color = "red";
                $("#extCount").show(800);

                var ibc = document.getElementsByClassName("install");
                var i;
                for (i = 0; i < ibc.length; i++) {
                    if (ibc[i].value.split(":")[0] == extName) {
                        ibc[i].disabled = false;
                    }
                }

                var dbc = document.getElementsByClassName("delete");
                var j;
                for (j = 0; j < dbc.length; j++) {
                    if(dbc[j].value.split(":")[0] == extName) {
                        dbc[j].disabled=true;
                    }
                }
            }
        },
        error: function()
        {
            alert('error!');
        }
        });
});
});
</script>

</head>
    <body>
        <center>
        <h1 id="tag">Extensions Management </br></h1>
        <h4 id="logout"><?php
            echo 'Welcome:'.$_SESSION['username']; ?> <a href="logout.php" style="color:#aa7750;"> [logout]</a>
            </br></br>
            You Have<p style="display: inline;" id="extCount"> <?php echo count($compList); ?></p> Extensions
        </h4>
        </center>
        <div id="loading"></div>
        <p id="result"></br></p><hr>
        <table id="content" border="1" style="margin: 0 auto;">
            <tr>
                <th>ID</th>
                <th>Extension</th>
                <th>Current Version</th>
                <th>Releases</th>
                <th>Depending On</th>
                <th>Operations</th>
            </tr>
            <?php foreach ($satisList as $key => $val){ $counter++; ?>
            <tr>
                <td><?php echo $counter; ?></td>
                <td><?php echo "$key"; ?></td>
                <td><?php echo $compList[$key]; ?></td>
                <td><select id=<?php echo $key;?> name="release">
                        <?php foreach(array_keys($satisList[$key]) as $paramName) {
                                    echo "<option value = ".$paramName." >". $paramName."</option >";
                              }
                        ?>
                    </select>
                </td>
                <td><select name="require">
                        <?php foreach(array_keys($satisList[$key]["dev-master"]["require"]) as $paramName2) {
                                    echo "<option value = ".$paramName2." >". $paramName2."</option >";
                              }
                        ?>
                    </select>
                </td>
                <td style="width:500px" >
                         <?php if (array_key_exists($key, $compList)) {
                                    echo '<a href="#tag"> <button  disabled class="install" type="button" value='.$key.':'.$val.' >Install</button></a> ';
                                    echo '<a href="#tag"> <button  class="delete" type="button" value='.$key.':'.$val.'>Delete</button></a>';
                               }
                                else {
                                    echo '<a href="#tag"> <button class="install" type="button" value='.$key.':'.$val.'>Install</button></a>';
                                    echo '<a href="#tag"><button disabled class="delete" type="button" value='.$key.':'.$val.'>Delete</button></a>';
                                }
                         ?>
                </td>
            </tr>
            <?php } ?>
        </table>
        </br>
    </body>
</html>
