<?php
    move_uploaded_file($_FILES['photo']['tmp_name'], './images/' . $_FILES['photo']['name']);
?>

<!-- <?php
    $source_file = $_FILES['photo']['tmp_name'];
    $destination = './images/' . $_FILES['photo']['name'];
    $quality = 20; // Compression quality, can be adjusted

    $info = getimagesize($source_file);

    if ($info['mime'] == 'image/jpeg') 
        $image = imagecreatefromjpeg($source_file);

    elseif ($info['mime'] == 'image/gif') 
        $image = imagecreatefromgif($source_file);

    elseif ($info['mime'] == 'image/png') 
        $image = imagecreatefrompng($source_file);

    imagejpeg($image, $destination, $quality);
    imagedestroy($image);

    move_uploaded_file($destination, './images/' . $_FILES['photo']['name']);
?> -->


<!-- <?php
  $file_name = $_FILES['photo']['tmp_name'];
  $file_type = $_FILES['photo']['type'];
  $file_size = $_FILES['photo']['size'];
  $target_file = './images/' . $_FILES['photo']['name'];
  
  $image = imagecreatefromjpeg($file_name);
  $original_width = imagesx($image);
  $original_height = imagesy($image);
  
  $ratio = $original_width / $original_height;
  $target_width = 500;
  $target_height = $target_width / $ratio;
  
  $new_image = imagecreatetruecolor($target_width, $target_height);
  imagecopyresampled($new_image, $image, 0, 0, 0, 0, $target_width, $target_height, $original_width, $original_height);
  
  $quality = 50; // adjust this value to change the compression level
  while (ob_get_level()) { ob_end_clean(); }
  header('Content-Type: image/jpeg');
  imagejpeg($new_image, $target_file, $quality);
  imagedestroy($image);
  imagedestroy($new_image);
  
  move_uploaded_file($target_file, $file_name);
?> -->
