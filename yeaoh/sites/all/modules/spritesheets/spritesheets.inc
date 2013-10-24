<?php
// $Id: spritesheets.inc,v 1.1 2011/01/11 17:41:21 tomfriedhof Exp $

/**
 * @file
 *
 * This file provides the sprite aggregation algorithm and helper functions
 */

define('SPRITE_SNAP', 32);

function spritesheets_walk($path, $filter = ".*") {
  $files = array();
  if ( $dir = array_diff(scandir($path), array('.', '..')) ) {
    foreach ( $dir as $item ) {
      $p = "$path/$item";
      if ( is_file($p) && preg_match("@.$filter\$@", $p) && !readlink($p) ) $files[] = $p;
      if ( is_dir($p) ) $files = array_merge($files, spritesheets_walk($p, $filter));
    }
  }
  return $files;
}

function spritesheets_mkdirs($path) {
  return is_dir($path) || ( spritesheets_mkdirs(dirname($path)) and mkdir($path) );
}

function spritesheets_compile($force = FALSE) {
  $ss_path = spritesheets_spritesheet_path("folder") . "/spritesheet";
  $qstring = '?' . variable_get('css_js_query_string', '1');

  if ( !$force && file_exists("$ss_path.css") ) return "$ss_path.css";

  if ( !$spritesheet_images = variable_get('spritesheets_images') ) {
    drupal_set_message(t('There are currently no images selected to be compiled into a spritesheet'));
    return;
  }


  $selectors = array();
  $images    = array();
  $sheets    = array();

  foreach ( variable_get('spritesheets_paths', array()) as $path ) $sheets = array_merge($sheets, spritesheets_walk($path, 'css'));
  foreach ( $sheets as $sheet ) {
    if ( $sheet == "$ss_path.css" ) continue;
    $css = file_get_contents($sheet);
    $css = str_replace(array("\n", "\t"), '', $css);
    $css = preg_replace(array('@ +@', '@/\*.+?\*/@'), array(' ', ''), $css);
    $css = str_replace('{}', '{ }', $css);

    preg_match_all('@(.+?) *{(.+?)}@', $css, $matches);
    list(, $s, $p) = $matches;

    foreach ( $s as $k => $selector ) {
      $selector = trim($selector);
      $properties = explode(';', $p[$k]);
      foreach ( $properties as $property ) {
        if ( !preg_match('@background(?:-image|-position)? ?: ?(?:url\([\'"]?(.+?)[\'"]?\))?(.*?)$@',
          $property, $matches) ) continue;
        list(, $img, $rest) = $matches;
        if ( preg_match('@(?<!no-)repeat@', $rest) || preg_match('@(-moz|-webkit)@', $rest) ) continue;
        preg_match_all('@(-?(?:0|\d+(?:%|px)|top|left|bottom|right|center))@', $rest, $matches);
        $left = $top = NULL;
        if ( $matches[1] && count($matches[1]) > 1 ) list($left, $top) = str_replace( array('top', 'right', 'bottom', 'left', 'center'), '0', $matches[1] );
        if ( $left ) $left = -$left;
        if ( $top  ) $top  = -$top;
        if ( $img ) {
          if ( $img[0] != '/' ) $img = '/' . substr($sheet, 0, strrpos($sheet, '/') + 1) . $img;
          $img = substr($img, 1);
          if ( !file_exists($img) ) continue;
          if ( !isset($images[$hash = md5(file_get_contents($img))]) ) {
            $image_info = getimagesize($img);
            $images[$hash] = (object) array(
              'file' => $img,
              'type' => substr($mime = $image_info['mime'], strpos($mime, '/') + 1),
              'width' => $w = $image_info[0],
              'height' => $h = $image_info[1],
              'w' => ceil($w / SPRITE_SNAP),
              'h' => ceil($h / SPRITE_SNAP),
            );
          }
        }
        foreach ( explode(',', $selector) as $ss ) {
          $selectors[$ss] = (object) array(
            'img' => $img,
            'left' => $left,
            'top' => $top,
            'css' => $sheet,
            'code' => $properties,
          );
        }
      }
    }
  }
  foreach ( $selectors as $k => &$selector ) {
    $key = $k;
    while ( preg_match('@(.+?)[ \.#:][^ \.#:]+? *$@', $k, $match) ) {
      $k = $match[1];
      if ( array_key_exists($k, $selectors) && $selectors[$k]->css == $selector->css ) {
        if ( !$selector->img ) $selector->img  = $selectors[$k]->img;
        if ( $selector->left == NULL ) $selector->left = $selectors[$k]->left;
        if ( $selector->top == NULL ) $selector->top  = $selectors[$k]->top;
      }
    }
  }

  foreach ( $selectors as $k => $v ) if ( !$v->img ) unset($selectors[$k]);
  foreach ( $images as $k => $image ) {
    if ( in_array($image->file, $spritesheet_images) ) continue;
    foreach ( $selectors as $h => $selector ) if ( $image->file == $selector->img ||
      !in_array($selector->img, $spritesheet_images) ) unset($selectors[$h]);
    unset($images[$k]);
  }

  uasort($images, '_spritesheets_sort_images');

  $w = 0;
  $h = 0;
  unset($image);
  foreach ( $images as $hash => &$image ) {
    $x = -1;
    $y = 0;
    $valid = FALSE;
    while ( !$valid ) {
      $x++;
      if ( $x >= $w ) {
        $x = 0;
        $y++;
      }
      if ( $y >= $h ) {
        $horiz = max($image->h, $h) * ($image->w + $w);
        $verti = max($image->w, $w) * ($image->h + $h);
        if ( $w < 16 ) $horiz = 0;
        if ( $horiz > $verti || $image->w + $w > 32 ) {
          $x = 0;
          $y = $h;
        }
        else {
          $x = $w;
          $y = 0;
        }
        $valid = TRUE;
        break;
      }
      $collides = FALSE;
      $image->x = $x;
      $image->y = $y;
      if ( _spritesheets_out_of_bounds($w, $h, $image) ) $collides = TRUE;
      foreach ( $images as $ih => $i ) {
        if ( $collides || $hash == $ih ) break;
        if ( _spritesheets_intersects($image, $i) ) $collides = TRUE;
      }
      if ( !$collides ) break;
    }
    $image->x = $x;
    $image->y = $y;
    $w = max($x + $image->w, $w);
    $h = max($y + $image->h, $h);
    unset($image);
  }
  $width = 0;
  $height = 0;
  foreach ( $images as &$image ) {
    $image->left = $image->x * SPRITE_SNAP;
    $image->top  = $image->y * SPRITE_SNAP;
    $width  = max($image->left + $image->width, $width );
    $height = max($image->top + $image->height, $height);
  }
  $css = array();

  unset($selector);
  foreach ( $selectors as $k => $selector ) {
    unset($image);
    $image = $images[$hash = md5(file_get_contents($selector->img))];
    $left = $image->left + $selector->left;
    $top  = $image->top  + $selector->top;
    $css[] = "$k { background: url(\"spritesheet.png$qstring\") -{$left}px -{$top}px no-repeat; }";
    unset($selector);
  }

  spritesheets_mkdirs(spritesheets_spritesheet_path("folder"));
  file_put_contents("$ss_path.css", implode("\n", $css));

  $sheet = imagecreatetruecolor($width, $height);
  imagealphablending($sheet, TRUE);
  imagesavealpha($sheet, TRUE);
  imagefill($sheet, 0, 0, imagecolorallocatealpha($sheet, 0, 0, 0, 127));

  if ( variable_get('spritesheets_debug') ) {
    $grid = imagecreatefrompng(drupal_get_path('module', 'spritesheets') . "/images/viewport.png");
    imagesettile($sheet, $grid);
    imagefill($sheet, 0, 0, IMG_COLOR_TILED);
    imagedestroy($grid);
  }

  $red = imagecolorallocate($sheet, 255, 0, 0);
  foreach ( $images as $image ) {
    if ( function_exists($f="imagecreatefrom{$image->type}") ) $sprite = $f($image->file);
    else continue;
    imagecopy($sheet, $sprite, $image->left, $image->top, 0, 0, $image->width, $image->height);
    imagedestroy($sprite);

    if ( variable_get('spritesheets_debug') ) {
      list($x, $y, $w, $h) = array($image->left, $image->top, $image->width, $image->height);

      imagerectangle($sheet, $x, $y, $x + $w -1, $y + $h -1, $red);
      imageline($sheet, $x, $y, $x + $w -1, $y + $h -1, $red);
      imageline($sheet, $x, $y + $h -1, $x + $w -1, $y, $red);
    }
  }

  imagepng($sheet, "$ss_path.png");

  if ( variable_get('spritesheets_optimize') && file_exists("sites/all/libraries/pngcrush/pngcrush") ) {
    $size_uncompressed = round(filesize("$ss_path.png") / 1024, 2);
    $result = exec("sites/all/libraries/pngcrush/pngcrush -rem cHRM -rem iCCP -rem sRGB -rem gAMA -rem alla -rem text $ss_path.png $ss_path-fs8.png 2>&1");
    $size_compressed = round(filesize("$ss_path-fs8.png") / 1024, 2);
    if ( $size_uncompressed > $size_compressed && $size_compressed != 0 ) {
      exec("mv $ss_path-fs8.png $ss_path.png");
      drupal_set_message(t("Using pngcrush to optimize the spritesheet has brought the image size from @uncompressedkB to @compressedkB.", array('@uncompressed' => $size_uncompressed, '@compressed' => $size_compressed)));
    }
    else {
      exec("rm $ss_path-fs8.png");
      drupal_set_message(t("Using pngcrush failed to compress the spritesheet; there may be a problem with the binary you've provided."));
    }
  }

  return "$ss_path.css$qstring";
}

function _spritesheets_sort_images($a, $b) {
  if ( ($aa = (pow($a->w, 2))*(pow($a->h, 2))) == ($bb = (pow($b->w, 2))*(pow($b->h, 2))) ) return 0;
  return $aa > $bb ? -1 : 1;
}

function _spritesheets_out_of_bounds($w, $h, $a) {
  return ( $a->x < 0 || $a->y < 0 || $a->x + $a->w > $w || $a->y + $a->h > $h );
}

function _spritesheets_intersects($a, $b) {
  return !( $a->x >= $b->x+$b->w || $a->x+$a->w <= $b->x || $a->y >= $b->y+$b->h || $a->y+$a->h <= $b->y );
}

