<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 07.03.2016
 * Time: 11:50
 */

namespace App\Http\Controllers;


use App\Album;
use App\Order;
use App\Photo;
use App\Size;
use SplFileInfo;
use Illuminate\Http\Request;

use App\Http\Requests;

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

    public function order(Request $request)
    {
        if (!$request->firstname or !$request->lastname or !$request->address or !$request->zip
        or !$request->city or !$request->finish or !$request->price or !$request->album
        or !$request->photos or !$request->agb) {
            return \Response::json([
                'error' => [
                    'message' => 'Please Provide all required fields'
                ]
            ], 422);
        }

        $o = [
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'street' => $request->address,
            'zip' => $request->zip,
            'city' => $request->city,
            'email' => $request->email,
            'finish' => $request->finish,
            'price' => $request->price,
            'remark' => $request->remark,
            'album_id' => $request->album['id']
        ];

        $order = Order::create($o);

        $id = $order->id;
        $path = $request->album['path'];

        $photos = $request->photos;
        $error = false;
        foreach ($photos as $key => $p) {
            foreach ($p as $entry) {
                $entry['order_id'] = $id;
                $entry['path'] = $path . '/' . $key;
                $entry['count'] = $entry['piece'];

                $s = Size::find($entry['size']);
                if (!$s) {
                    $error = true;
                    break;
                }

                $entry['size'] = $s->text;
                unset($entry['piece']);
                Photo::create($entry);
            }
            if ($error) {
                break;
            }
        }

        if ($error) {
            $order->deleted = true;
            $order->save();
            return \Response::json([
                'error' => [
                    'message' => 'Error while saving Photos'
                ]
            ], 422);
        }

        //TODO SendMail

        return \Response::json([
            'data' => 'created'
        ], 201);
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