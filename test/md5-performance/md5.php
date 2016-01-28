
<?php
    $startTime = microtime(true);  
    
    for($i = 0; $i < 100; $i++)
        exec('md5sum ' . 'a.tpl.php');
    
    $endTime = microtime(true);  
    $elapsed = $endTime - $startTime;
    echo "MD5SUM : $elapsed seconds <br>";
    
    
    $startTime = microtime(true);  
    for($i = 0; $i < 100; $i++)
        md5_file('a.tpl.php');
    $endTime = microtime(true);  
    $elapsed = $endTime - $startTime;
    echo "MD5_FILE : $elapsed seconds <br>";
    
    $startTime = microtime(true);  
    for($i = 0; $i < 100; $i++)
        filemtime('a.tpl.php');
    $endTime = microtime(true);  
    $elapsed = $endTime - $startTime;
    echo "FILEMTIME : $elapsed seconds <br>";
    
    $startTime = microtime(true);  
    for($i = 0; $i < 100; $i++)
        file_get_contents('a.tpl.php');
    $endTime = microtime(true);  
    $elapsed = $endTime - $startTime;
    echo "FILE_GET_CONTENTS : $elapsed seconds";
    
    //http://stackoverflow.com/questions/3880149/best-way-to-measure-and-refine-performance-with-php
?>