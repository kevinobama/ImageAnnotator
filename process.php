<?php

$func = $_REQUEST['f'];

if ($func == 'load') {
  $images = array();
  $dir = opendir('data/images');
  while ($f = readdir($dir)) {
    if (substr($f, -4) == '.jpg' || substr($f, -4) == '.png') {
      $images[] = $f;
    }
  }
  closedir($dir);
  $total_images = count($images);
  $total_annotated = floor(shell_exec("ls -1 data/images/*.txt | wc -l"));
  shuffle($images);
  foreach($images as $f) {
    $txt = substr($f, 0, strlen($f) - 4);
    if (!file_exists("data/images/$txt.txt")) {
      header('Content-Type: application/json');
      echo "{\"image\":\"$f\",\"total_images\":$total_images,\"total_annotated\":$total_annotated,\"classes\":[";
      $list = explode("\n", file_get_contents('data/classes.txt'));
      $first = true;
      foreach($list as $l) {
        if (trim($l)=="") continue;
        if (!$first) echo ',';
        $first = false;
        echo "\"$l\"";
      }
      echo "]}\n";
      return;
    }
  }
  return;
}
else if ($func == 'save') {
  print_r($_REQUEST);
  exit;
  $json = json_decode($_REQUEST['classes']);
  $fnimg = $_REQUEST['image'];
  $fn = substr($fnimg, 0, strlen($fnimg) - 4);
  /*
  $xml = "<annotation>
  <folder>data</folder>
  <filename>$fn</filename>
  <path>data/images/$fn</path>
  <source>
    <database>Unknown</database>
  </source>
  <size>
    <width>$imwidth</width>
    <height>$imheight</height>
    <depth>3</depth>
  </size>
  <segmented>0</segmented>
  ";
  foreach($json as $j) {
    $xml .= "<object>
      <name>$j->class</name>
      <bndbox>
        <xmin>".($j->x1<=$j->x2?$j->x1:$j->x2)."</xmin>
        <xmax>".($j->x1>$j->x2?$j->x1:$j->x2)."</xmax>
        <ymin>".($j->y1<=$j->y2?$j->y1:$j->y2)."</ymin>
        <ymax>".($j->y1>$j->y2?$j->y1:$j->y2)."</ymax>
      </bndbox>
    </object>";
  }
  $xml .= "</annotation>\n";
  file_put_contents("data/images/$fn.xml", $xml);
  * */
  $txt = '';
  // Save as Darknet txt also
  foreach($json as $j) {
    if ($j->x2 > $j->x1) {
      $bw = $j->x2 - $j->x1; 
      $x = $j->x1;
    }
    else {
      $bw = $j->x1 - $j->x2;
      $x = $j->x2;
    }
    if ($j->y2 > $j->y1) {
      $bh = $j->y2 - $j->y1; 
      $y = $j->y1;
    }
    else {
      $bh = $j->y1 - $j->y2; 
      $y = $j->y2;
    }
    //  Bounding box midpoint
    $x += $bw / 2;
    $y += $bh / 2;
    $txt .= $j->class." $x $y $bw $bh\n";
  }
  file_put_contents("data/images/$fn.txt", $txt);
  echo "{\"status\":\"ok\"}\n";
}
else if ($func == 'seg') {
  $j = json_decode($_REQUEST['segment']);
  $fnimg = $_REQUEST['image'];
  $img = imagecreatefromjpeg("data/images/$fnimg");
  if ($j->x2 > $j->x1) {
    $bw = $j->x2 - $j->x1; 
    $x = $j->x1;
  }
  else {
    $bw = $j->x1 - $j->x2;
    $x = $j->x2;
  }
  if ($j->y2 > $j->y1) {
    $bh = $j->y2 - $j->y1; 
    $y = $j->y1;
  }
  else {
    $bh = $j->y1 - $j->y2; 
    $y = $j->y2;
  }
  $w = imagesx($img) * $bw;
  $h = imagesy($img) * $bh;
  $im2 = imagecreatetruecolor($w, $h);
  imagecopy($im2, $img, 0, 0, $x*imagesx($img) , $y*imagesy($img), $w, $h);
  imagedestroy($img);
  header('Content-Type: image/jpeg');
  imagejpeg($im2);
  imagedestroy($im2);
}
