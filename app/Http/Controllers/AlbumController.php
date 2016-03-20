<?php

namespace App\Http\Controllers;

use App\Album;
use Illuminate\Http\Request;

use App\Http\Requests;
use SplFileInfo;

class AlbumController extends Controller
{
    public function __construct(){
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $sizes = Album::where('public', '=' ,true)->where('deleted', '=', false)->get();
        return \Response::json([
            'data' => $this->transformCollection($sizes)
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

        return \Response(200);
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
            'photos' => $this->imagesInDir($album['path'])
        ];
    }

    private function imagesInDir($dir) {
        $files = \Storage::files("public/albums/" . $dir);

        $result = [];
        foreach($files as $file) {
            $f = new SplFileInfo($file);
            $ex = strtolower($f->getExtension());
            if ($ex === "png" || $ex === "gif" || $ex === 'bmp' || $ex === "jpeg" || $ex === "jpg") {
                $result[] = $f->getFilename();
            }
        }
        return $result;
    }
}
