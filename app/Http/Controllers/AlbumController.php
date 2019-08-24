<?php

namespace App\Http\Controllers;

use App\Album;
use Illuminate\Http\Request;

use App\Http\Requests;
use SplFileInfo;

class AlbumController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function index()
    {
        $albums = Album::where('deleted', '=', false)->get();
        return \Response::json([
            'data' => $this->transformCollection($albums)
        ], 200);
    }

    public function show($id)
    {
        if (is_numeric($id)) {
            $album = Album::find($id);
        } else {
            $album = Album::where('key', '=', $id)->first();
        }

        if (!$album) {
            return \Response::json([
                'error' => [
                    'message' => 'Album does not exist'
                ]
            ], 404);
        }

        return \Response::json([
            'data' => $this->transform($album)
        ], 200);
    }

    public function store(Request $request)
    {

        if (!$request->name or !$request->path) {
            return \Response::json([
                'error' => [
                    'message' => 'Please Provide Both name and path'
                ]
            ], 422);
        }

        $album = Album::create($request->all());

        if (!$request->public) {
            $album->key = Album::generateKey();
            $album->save();
        }

        if (!\File::exists($this->getAlbumDir($album->path))) {
            \File::makeDirectory($this->getAlbumDir($album->path), 0777, true, true);
        }

        return \Response::json([
            'message' => 'Size Created Succesfully',
            'data' => $this->transform($album)
        ], 201);
    }

    public function update(Request $request, $id)
    {
        if (!$request->name or !$request->path) {
            return \Response::json([
                'error' => [
                    'message' => 'Please Provide Both name and path'
                ]
            ], 422);
        }

        $album = Album::find($id);

        if ($album->path != $request->path) {
            \File::deleteDirectory($this->getAlbumDir($album->path));
            \File::makeDirectory($this->getAlbumDir($request->path), 0777, true, true);
        }

        if ($album->public && !$request->public) {
            $album->key = Album::generateKey();
        } elseif (!$album->public && $request->public) {
            $album->key = null;
        }

        $album->name = $request->name;
        $album->path = $request->path;
        $album->public = $request->public;
        $album->save();

        return \Response::json([
            'message' => 'Album Updated Succesfully'
        ]);
    }

    public function destroy($id)
    {
        $album = Album::find($id);
        if (!$album) {
            return \Response::json([
                'error' => [
                    'message' => 'Album does not exist'
                ]
            ], 404);
        }

        $album->deleted = true;
        $album->save();

        //\File::deleteDirectory($this->getAlbumDir($album->path)); //TODO Ask Customer

        return \Response(200);
    }

    public function processImagesOfAlbum($id)
    {
        $album = Album::find($id);
        if (!$album) {
            return \Response::json([
                'error' => [
                    'message' => 'Album does not exist'
                ]
            ], 404);
        }

        $images = $this->imagesInDir($this->getAlbumDir($album->path));

        foreach ($images as $image) {
            $imgPath = $this->getAlbumDir($album->path . '/' . $image[0]);
            switch ($image[1]) {
                case "png":
                    $im = imagecreatefrompng($imgPath);
                    list($width, $height) = getimagesize($imgPath);
                    $this->createThumb($im, $width, $height, $image[1], $this->getAlbumDir($album->path), $image[0], env('SHOP_SMALLIMAGESIZE'), 't_');
                    $this->createThumb($im, $width, $height, $image[1], $this->getAlbumDir($album->path), $image[0], env('SHOP_IMAGESIZE'), 'c_');
                    imagedestroy($im);
                    $im = imagecreatefrompng($this->getAlbumDir($album->path . '/c_' . $image[0]));
                    $this->drawLines($im, $width, $height);
                    imagepng($im, $this->getAlbumDir($album->path . '/c_' . $image[0]));
                    imagedestroy($im);
                    break;
                case "jpg":
                case "jpeg":
                    $im = imagecreatefromjpeg($imgPath);
                    list($width, $height) = getimagesize($imgPath);
                    $this->createThumb($im, $width, $height, $image[1], $this->getAlbumDir($album->path), $image[0], env('SHOP_SMALLIMAGESIZE'), 't_');
                    $this->createThumb($im, $width, $height, $image[1], $this->getAlbumDir($album->path), $image[0], env('SHOP_IMAGESIZE'), 'c_');
                    imagedestroy($im);
                    $im = imagecreatefromjpeg($this->getAlbumDir($album->path . '/c_' . $image[0]));
                    $this->drawLines($im, $width, $height);
                    imagejpeg($im, $this->getAlbumDir($album->path . '/c_' . $image[0]));
                    imagedestroy($im);
                    break;
                default:
                    return \Response(422);
            }
            \File::delete($imgPath);
        }

        $this->fixPermissionsInDir($this->getAlbumDir($album->path));

        return \Response(200);
    }

    private function drawLines($image, $width, $height)
    {
        $color = imagecolorallocate($image, 255, 255, 255);
        imagesetthickness($image, 3);
        $stepSize = 200;

        if ($width > $height) {

            $x = $width - $stepSize;
            $end = $stepSize;
            while ($x > -$width) {
                imageline($image, $x, 0, $width, $end, $color);
                $x -= $stepSize;
                $end += $stepSize;
            }
        } else {
            $y = $height - $stepSize;
            $end = $stepSize;
            while ($y > -$height) {
                imageline($image, 0, $y, $end, $height, $color);
                $y -= $stepSize;
                $end += $stepSize;
            }
        }
    }

    private function createThumb($image, $width, $height, $ex, $path, $name, $size, $prefix)
    {
        if ($width > $height) {
            $thumb_w = $size;
            $thumb_h = $height * ($size / $width);
        } elseif ($width < $height) {
            $thumb_w = $width * ($size / $height);
            $thumb_h = $size;
        } else {
            $thumb_w = $size;
            $thumb_h = $size;
        }
        if ($thumb_w < $width || $thumb_h < $height) {
            $dst_img = imagecreatetruecolor($thumb_w, $thumb_h);

            if ($ex == 'png') {
                // integer representation of the color black (rgb: 0,0,0)
                $background = imagecolorallocate($dst_img, 0, 0, 0);
                // removing the black from the placeholder
                imagecolortransparent($dst_img, $background);

                // turning off alpha blending (to ensure alpha channel information
                // is preserved, rather than removed (blending with the rest of the
                // image in the form of black))
                imagealphablending($dst_img, false);

                // turning on alpha channel information saving (to ensure the full range
                // of transparency is preserved)
                imagesavealpha($dst_img, true);
                imagecopyresampled($dst_img, $image, 0, 0, 0, 0, $thumb_w, $thumb_h, $width, $height);
                imagepng($dst_img, $path . '/' . $prefix . $name);
            } else {
                imagecopyresampled($dst_img, $image, 0, 0, 0, 0, $thumb_w, $thumb_h, $width, $height);
                imagejpeg($dst_img, $path . '/' . $prefix . $name);
            }
            imagedestroy($dst_img);
        } else {
            if ($ex == 'png') {
                imagepng($image, $path . '/' . $prefix . $name);
            } else {
                imagejpeg($image, $path . '/' . $prefix . $name);
            }
        }
    }

    private function getAlbumDir($path)
    {
        return public_path() . "/albums/" . $path;
    }

    private function transformCollection($albums)
    {
        return array_map([$this, 'transform'], $albums->toArray());
    }

    private function transform($album)
    {
        return [
            'id' => $album['id'],
            'path' => $album['path'],
            'name' => $album['name'],
            'public' => $album['public'],
            'key' => $album['key']
        ];
    }

    private function imagesInDir($dir)
    {
        $files = \File::files($dir);

        $result = [];
        foreach ($files as $file) {
            $f = new SplFileInfo($file);
            $ex = strtolower($f->getExtension());
            if ($ex === "png" || $ex === "jpeg" || $ex === "jpg") {
                if (starts_with($f->getFilename(), 't_') || starts_with($f->getFilename(), 'c_')) {
                    continue;
                }
                $result[] = [$f->getFilename(), $ex];
            }
        }
        return $result;
    }

    private function fixPermissionsInDir($dir) {
      $files = \File::files($dir);

      foreach ($files as $file) {
        $f = new SplFileInfo($file);
        $ex = strtolower($f->getExtension());
        if ($ex === "png" || $ex === "jpeg" || $ex === "jpg") {
          chmod($file, 0664);
        }
      }
    }

}
