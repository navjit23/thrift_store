<?php

function imageResize($image){
    $og_image = imagecreatefrompng($image);
    $width = imagesx($og_image);
    $height= imagesy($og_image);

    $newwidth= 400;
    $newheight= 400;
    $new_image= imagecreate($newwidth,$newheight);

    imagecopyresized($new_image,$og_image,0,0,0,0,$newwidth,$newheight,$width,$height);
    imagepng($new_image,$image);
    imagedestroy($og_image);
    imagedestroy($new_image);
}

$image_path = $_GET['path'];
imageResize($image_path);
echo"success";
header("Location:index.php");
?>