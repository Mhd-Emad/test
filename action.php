<?php

    $compFile = file_get_contents($_SERVER['DOCUMENT_ROOT']."/composer.json");
    $compList = json_decode($compFile, true);
    $compList = $compList['require'];
    $extCount = count($compList);

    $result = array();
    $res    = " ";

    if($_GET["action"]=="install")
        install($_GET['ext'],$_GET['version']);
    else
        if($_GET["action"] == delete)
            delete($_GET['ext']);


function install($extName, $extVersion)
{
    global $compList,$extCount,$result,$res;

    $satisFile = file_get_contents('http://list.satis.shopgo.io/');
    $satisList = json_decode($satisFile,true);
    $satisList = $satisList["packages"][$extName]["dev-master"]["require"];

    foreach ($satisList as $key => $val) {
        if (!array_key_exists($key, $compList)) {
            $result[0] = $extName . " Extension Depend on <p style='color:#FF6611; display: inline;'> ".$key."<p>";
            $result[1] = "null";
            echo json_encode($result);

            exit;
        }
    }

    try {
        if (file_exists($_SERVER['DOCUMENT_ROOT']."/composer.json") == false) {
            $result[0] = 'Sorry, you must to setup composer.json before install any extension';
            $result[1] = "null";
            echo json_encode($result);
            exit();
        }

        $cmd="cd ".$_SERVER['DOCUMENT_ROOT'].' && /opt/nexcess/php54u/root/usr/bin/php /usr/bin/composer require '.$extName.':'.$extVersion." 2>&1";
        while (@ ob_end_flush()); // end all output buffers if any
        $proc = popen($cmd, 'r');
        $res=$res.'<p>';
        while (!feof($proc))
        {
            $res= $res. fread($proc, 4096);
            $res= $res. "</br>";
            @ flush();
        }
        $res = $res.'</p>';


        $extCountAfter = extCount();
        $result[0]     = $res;

        if ($extCountAfter>$extCount) {
            $result[1] = $extCountAfter;
        }
        else {
            $result[1] = "null";
        }

        echo json_encode($result);
    }
    catch (Exception $e) {
        $result[0]= 'Caught exception: '. $e->getMessage(). "\n";
        echo json_encode($result);
    }

}


function delete($extName)
{
    global $extCount,$result,$res;

    try {
        $cmd = "cd ".$_SERVER['DOCUMENT_ROOT'].' && /opt/nexcess/php54u/root/usr/bin/php /usr/bin/composer remove '.$extName." 2>&1";
        $res = $res.'<p>';

        while (@ ob_end_flush()); // end all output buffers if any
        $proc = popen($cmd, 'r');
        $res=$res.'<p>';
        while (!feof($proc))
        {
            $res = $res. fread($proc, 4096);
            $res = $res. "</br>";
            @ flush();
        }
        $res =$res.'</p>';


        $extCountAfter = extCount();
        $res           = $res.shell_exec('find . ! -path "./app/etc/modules/disabled/*" -type l -xtype l -delete');
        $result[0]     = $res;

        if ($extCountAfter<$extCount) {
            $result[1] = $extCountAfter;
        }
        else {
            $result[1] = "null";
        }

        echo json_encode($result);

    } catch (Exception $e) {
        $result[0]='Caught exception: '. $e->getMessage(). "\n";
        echo json_encode($result);
    }
}

function extCount()
{
    $compFile = file_get_contents($_SERVER['DOCUMENT_ROOT']."/composer.json");
    $compList = json_decode($compFile, true);
    $compList = $compList['require'];
    return $extCountAfter=count($compList);

}