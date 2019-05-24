<?php

namespace Dot;

abstract class Images {
	static function resize($original, $width, $rename){
		$rename = $rename ?: $original;

		if (!is_file($original)) {
			return null;
		}

		$size = @getimagesize($original);
		$format = $size[2];

		if (
			($format < 1 || $format > 3) || // gif, jpg, png
			($format == 1 && is_animated_gif($original)) // animated gif
		) {
			return false;
		}

		$height = \round($width * $size[1]) / $size[0];
		$method = __NAMESPACE__ . '\Images::resize' . ([1 => 'Gif', 2 => 'Jpg', '3' => 'Png'][$format]);
		$method($original, $size, $width, $height, $rename);
	}

	static function resizeGif($original, $size, $width, $height, $rename)
	{
		$source = \imagecreatefromgif($original);
		$transparent = \imagecolortransparent($source);

		$resize = \imagecreatetruecolor($width, $height);

		$totalColors = \imagecolorstotal($resize);
		if ($transparent >= 0 && $transparent < $totalColors) {
			$transparentColor = \imagecolorsforindex($source, $transparent);
			$current = \imagecolorallocate($resize, $transparentColor['red'], $transparentColor['green'], $transparentColor['blue']);
			\imagefill($resize, 0, 0, $current);
			\imagecolortransparent($resize, $current);
		} else {
			$background = \imagecolorallocate($resize, 255, 255, 255);
			\imagefill($resize, 0, 0, $background);
		}

		\imagecopyresampled($resize, $source, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
		\imagegif($resize, $rename);

		\imagedestroy($source);
		\imagedestroy($resize);
	}

	static function resizeJpg($original, $size, $width, $height, $rename)
	{
		$source = \imagecreatefromjpeg($original);
		$resize = \imagecreatetruecolor($width, $height);

		\imagecopyresampled($resize, $source, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
		\imagejpeg($resize, $rename);

		\imagedestroy($source);
		\imagedestroy($resize);
	}

	static function resizePng($original, $size, $width, $height, $rename)
	{
		$source = \imagecreatefrompng($original);
		\imagealphablending($source, true);

		$resize = \imagecreatetruecolor($width, $height);

		$background = \imagecolorallocatealpha($resize, 0, 0, 0, 127);
		\imagefill($resize, 0, 0, $background);
		\imagealphablending($resize, false);
		\imagesavealpha($resize, true);

		\imagecopyresampled($resize, $source, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);

		\imagepng($resize, $rename);

		\imagedestroy($source);
		\imagedestroy($resize);
	}
}