<?php
if (($file = fopen('file.csv', 'r')) !== false)
{
    $csv = [];
    while (($line = fgetcsv($file, 1200)) !== false)
    {
        $stringt  = 'if ($uri == \''.$line[0].'\') {
        $title = \''.$line[3].'\';
        $descr = \''.$line[4].'\';
        $h1 = \''.$line[2].'\';
    }'.PHP_EOL;

        file_put_contents('sss.txt',$stringt,FILE_APPEND) ;

    }

//    fclose($handle);
}
