<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;

use App\Http\Requests;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
    }

    public function index()
    {
        $orders = Order::with('Photo')->with('Album')->where('deleted', '=', false)->get();
        return \Response::json([
            'data' => $this->transformCollection($orders)
        ], 200);
    }

    public function show($id)
    {
        $order = Order::with('Photo')->with('Album')->find($id);


        if (!$order) {
            return \Response::json([
                'error' => [
                    'message' => 'Order does not exist'
                ]
            ], 404);
        }

        return \Response::json([
            'data' => $this->transform($order)
        ], 200);
    }

    public function destroy($id)
    {
        $Order = Order::find($id);
        if (!$Order) {
            return \Response::json([
                'error' => [
                    'message' => 'Order does not exist'
                ]
            ], 404);
        }

        $Order->deleted = true;
        $Order->save();

        return \Response(200);
    }

    private function transformCollection($orders)
    {
        return array_map([$this, 'transform'], $orders->toArray());
    }

    private function transform($order)
    {
        return [
            'id' => $order['id'],
            'firstname' => $order['firstname'],
            'lastname' => $order['lastname'],
            'street' => $order['street'],
            'zip' => $order['zip'],
            'village' => $order['village'],
            'email' => $order['email'],
            'photos' => $this->transformPhotos($order['photo']),
            'price' => $order['price'],
            'finish' => $order['finish'],
            'album' => [
                'id' => $order['album']['id'],
                'name' => $order['album']['name']
            ]
        ];
    }

    private function transformPhotos($photos)
    {
        if (!is_array($photos)) {
            $photos = $photos->toArray();
        }
        return array_map([$this, 'transformPhoto'], $photos);
    }

    private function transformPhoto($photo) {
        return [
            'id' => $photo['id'],
            'path' => $photo['path'],
            'size' => $photo['size'],
            'price' => $photo['price']
        ];
    }
}
