<?php

namespace App\Http\Controllers;

use App\Size;
use Illuminate\Http\Request;

use App\Http\Requests;

class SizeController extends Controller
{

    public function __construct(){
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $sizes = Size::all();
        return \Response::json([
            'data' => $this->transformCollection($sizes)
        ], 200);
    }

    public function show($id)
    {
        $size = Size::find($id);

        if (!$size) {
            return \Response::json([
                'error' => [
                    'message' => 'Size does not exist'
                ]
            ], 404);
        }

        return \Response::json([
            'data' => $this->transform($size)
        ], 200);
    }

    public function store(Request $request)
    {

        if (!$request->text or !$request->price) {
            return \Response::json([
                'error' => [
                    'message' => 'Please Provide Both text and price'
                ]
            ], 422);
        }
        $size = Size::create($request->all());

        return \Response::json([
            'message' => 'Size Created Succesfully',
            'data' => $this->transform($size)
        ], 201);
    }

    public function update(Request $request, $id)
    {
        if (!$request->text or !$request->price) {
            return \Response::json([
                'error' => [
                    'message' => 'Please Provide Both text and price'
                ]
            ], 422);
        }

        $size = Size::find($id);
        $size->text = $request->text;
        $size->price = $request->price;
        $size->save();

        return \Response::json([
            'message' => 'Size Updated Succesfully'
        ]);
    }

    public function destroy($id)
    {
        Size::destroy($id);
    }

    private function transformCollection($sizes)
    {
        return array_map([$this, 'transform'], $sizes->toArray());
    }

    private function transform($size)
    {
        return [
            'id' => $size['id'],
            'text' => $size['text'],
            'price' => $size['price']
        ];
    }

}
