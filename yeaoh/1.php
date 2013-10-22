<?

ini_set('memory_limit', '64M');

$o_pic = '1.jpg';

//?????????
$begin_r = 170;
$begin_g = 200;
$begin_b = 190;

list($src_w,$src_h,$src_type) = getimagesize($o_pic);// ???????

$file_ext = "jpg"; //?????,?????,????????
$target_im = imagecreatetruecolor($src_w,$src_h);//??


if($file_ext == 'gif') //??GIF ??
{
    $src_im = ImageCreateFromGIF($o_pic);
      
    imagecopymerge($target_im, $src_im ,0,0,0,0,$src_w,$src_h,100);
    for($x = 0; $x < $src_w; $x++)
    {
        for($y = 0; $y < $src_h; $y++)
        {
            $rgb = imagecolorat($target_im, $x, $y);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;
//echo "{$rgb}\t{$r}\t{$g}\t{$b}\r\n";

            if($r > $begin_r && $g > $begin_g && $b > $begin_b ){
                //imagecolortransparent($target_im, imagecolorallocate($target_im,$r, $g, $b));
//imagecolorallocate($target_im, 254, 254, 254);
imagesetpixel($target_im, $x, $y,imagecolorallocate($target_im, 254, 254, 254));
            }
//imagecolortransparent($target_im, imagecolorallocate($target_im, $r, $g, $b));

        }
    }/**/
//filter_opacity($target_im, 10);

header("Content: image/gif"); 
imagepng($target_im); 
imagedestroy($target_im); 
}

?>