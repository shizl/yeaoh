11111111111111111
<?php
//print_r($node);
$my_field_items = field_get_items('node', $node, 'field_service_category');
if ($my_field_items) {
  print_r($my_field_items);
  $my_field_first_item = reset($my_field_items);
  print_r($my_field_first_item);
  $my_field_value = $my_field_first_item['tid'];
  var_dump($my_field_value);
  $tid = $my_field_value;

  $term = taxonomy_term_load($tid); // load term object
  $term_uri = taxonomy_term_uri($term); // get array with path
  $term_title = taxonomy_term_title($term);
  $term_path = $term_uri['path'];
  print_r($term_path);
  $link = l($term_title, $term_path);
  print_r($link);
}


$path = $term_path;

print_r(menu_set_active_item($path));


?>
<div id="node-service">
  <?php print render($content); ?>
</div>
