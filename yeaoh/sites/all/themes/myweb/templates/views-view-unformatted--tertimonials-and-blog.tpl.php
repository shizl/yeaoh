<?php

/**
 * @file
 * Default simple view template to display a list of rows.
 *
 * @ingroup views_templates
 */
?>
<?php

//echo "<pre>";
//print_r($rows);
//echo "</pre>";
//exit();

$bodys = '';
$images = '';
$names = '';
?>

<?php
/*
foreach ($rows as $row){
$str = $row;
$temp = explode('<div class="body">',$str);
$temp = explode('</div>',$temp['1']);
$body = $temp[0];
$bodys .= $body;
$str = $row;
$temp = explode('<div class="image">',$str);
$temp = explode('</div>',$temp['1']);
$image = $temp[0];
$images .= $image;
$str = $row;
$temp = explode('<div class="name">',$str);
$temp = explode('</div>',$temp['1']);
$name = $temp[0];
$names .= $name;
}
*/
?>


<?php foreach ($rows as $id => $row): ?>
  <div<?php if ($classes_array[$id]) { print ' class="' . $classes_array[$id] .'"';  } ?>>
    <?php print $row; ?>
  </div>
<?php endforeach; ?>
