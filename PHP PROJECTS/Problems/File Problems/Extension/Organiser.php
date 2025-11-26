<?php


$files = scandir('.');
print_r($files);

$extensions = array_map(function($file) {
    $ext = substr($file, strpos($file, '.') +1);
    return strlen($ext) > 2 ? $ext : null;
},$files);
print_r($extensions);

$fileTypes = array_unique($extensions);

print_r($fileTypes);

foreach($fileTypes as $type){
    if($type){
        if(!is_dir($type)){
            mkdir($type);
        }
    }
    foreach($files as $file){
        $ext = substr($file, strpos($file, '.')+1);
        if($ext === $type){
            rename($file,$type . '/' . $file);
        }
    }
}
?>

