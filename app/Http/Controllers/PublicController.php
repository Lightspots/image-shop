<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 07.03.2016
 * Time: 11:50
 */

namespace App\Http\Controllers;


use App\Album;
use SplFileInfo;

class PublicController extends Controller
{
    public function index()
    {
        return view('public.index');
    }

    public function publicAlbums()
    {
        $albums = Album::where('public', '=', 1)->where('deleted', '=', false)->get();
        return \Response::json([
            'data' => $this->transformAlbums($albums)
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

        $album = $album->toArray();
        $album['photos'] = $this->imagesInDir($this->getAlbumDir($album['path']));

        return \Response::json([
            'data' => $album
        ], 200);
    }

    private function transformAlbums($albums)
    {
        return array_map([$this, 'transformAlbum'], $albums->toArray());
    }

    private function transformAlbum($album)
    {
        return [
            'id' => $album['id'],
            'name' => $album['name'],
        ];
    }

    private function getAlbumDir($path)
    {
        return public_path() . "/albums/" . $path;
    }

    private function imagesInDir($dir)
    {
        $files = \File::files($dir);

        $result = [];
        foreach ($files as $file) {
            $f = new SplFileInfo($file);
            $ex = strtolower($f->getExtension());
            if ($ex === "png" || $ex === "jpeg" || $ex === "jpg") {
                if (starts_with($f->getFilename(), 'c_')) {
                    $str = substr($f->getFilename(), 2);
                    $result[] = $str;
                }

            }
        }
        return $result;
    }
}