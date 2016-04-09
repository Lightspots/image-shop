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
        $sizes = Album::where('deleted', '=', false)->get();
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

        if(!\File::exists(public_path(). "/albums/" . $album->path)) {
            \File::makeDirectory(public_path(). '/albums/' . $album->path, 0777, true, true);
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
            \File::deleteDirectory(public_path(). "/albums/" . $album->path);
            \File::makeDirectory(public_path(). '/albums/' . $request->path, 0777, true, true);
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

        //\File::deleteDirectory(public_path(). "/albums/" . $album->path); TODO Ask Customer

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
            'public' => $album['public'],
            'key' => $album['key']
        ];
    }

    private function imagesInDir($dir) { //TODO Move to correct controller
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
