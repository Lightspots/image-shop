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
use App\Preferences;
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
        \Log::info('New Order');
        if (!$request->firstname or !$request->lastname or !$request->address or !$request->zip
            or !$request->city or !$request->email or !$request->phone or !$request->finish or !$request->price or !$request->album
            or !$request->photos or !$request->agb
        ) {
            \Log::warn('Order: Not all required fields provided!');
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
            'phone' => $request->phone,
            'finish' => $request->finish,
            'price' => $request->price,
            'remark' => $request->remark,
            'album_id' => $request->album['id']
        ];

        $order = Order::create($o);

        $id = $order->id;
        $path = $request->album['path'];

        $photos = $request->photos;
        \Log::info("Start saving Photos");
        foreach ($photos as $entry) {
                $entry['order_id'] = $id;
                $entry['path'] = $path . '/' . $entry['photo'];
                $entry['count'] = $entry['piece'];
                unset($entry['piece']);
                Photo::create($entry);
        }

        \Log::info('Exit Order');
        return \Response::json([
            'data' => 'created'
        ], 201);
    }

    public function mail()
    {
        //Function called by Cronjob of Webserver!

        $orders = Order::with('Photo')->with('Album')->where('deleted', '=', false)->where('mailSend', '=', false)->get();
        $shippingCosts = Preferences::find('shippingCosts');

        foreach ($orders as $order) {
            foreach ($order->photo as $photo) {
                $path = explode('/', $photo->path);
                $photo->name = end($path);
            }

            \Mail::send('emails.customer', ['order' => $order, 'shippingCosts' => $shippingCosts], function ($message) use ($order) {
                $message->from(env('MAIL_ADDRESS'), $name = null);
                $message->to($order->email, $name = null);
                $message->cc(env('MAIL_ADDRESS'), $name = null);
                $message->subject('#' . $order->id . ':' . $order->album->name . ' - ' . env('MAIL_SUBJECT'));
            });
            $order->mailSend = true;
            $order->save();
        }
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