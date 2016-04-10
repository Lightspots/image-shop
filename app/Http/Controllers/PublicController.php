<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 07.03.2016
 * Time: 11:50
 */

namespace App\Http\Controllers;


use App\Album;

class PublicController extends Controller
{
    public function index()
    {
        return view('public.index');
    }

    public function publicAlbum()
    {
        $albums = Album::where('public', '=', 1)->where('deleted', '=', false)->get();
        return \Response::json([
            'data' => $this->transformAlbums($albums)
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
}