<?php

namespace App\Http\Controllers;

use App\Preferences;
use Illuminate\Http\Request;

use App\Http\Requests;

class SizeController extends Controller
{

    public function __construct(){
        $this->middleware('jwt.auth', ['except' => ['index', 'show']]);
    }

    public function index()
    {
        $pref = Preferences::all();
        return \Response::json([
            'data' => $this->transformCollection($pref)
        ], 200);
    }

    public function show($key)
    {
        $pref = Preferences::find($key);

        if (!$pref) {
            return \Response::json([
                'error' => [
                    'message' => 'Preference does not exist'
                ]
            ], 404);
        }

        return \Response::json([
            'data' => $this->transform($pref)
        ], 200);
    }

    public function update(Request $request)
    {
        if (!$request->key or !$request->value) {
            return \Response::json([
                'error' => [
                    'message' => 'Please Provide Both key and value'
                ]
            ], 422);
        }

        $pref = Preferences::find($request->key);

        if ($pref->type != 'aFlaot' || floatval($request->value) < 0) {
            return \Response::json([
                'error' => [
                    'message' => 'Value has wrong Type!'
                ]
            ], 422);
        }

        $pref->value = $request->value;
        $pref->save();

        return \Response::json([
            'message' => 'Preference Updated Succesfully'
        ]);
    }

    private function transformCollection($prefs)
    {
        return array_map([$this, 'transform'], $prefs->toArray());
    }

    private function transform($pref)
    {
        return [
            'key' => $pref['key'],
            'value' => $pref['value'],
        ];
    }

}
