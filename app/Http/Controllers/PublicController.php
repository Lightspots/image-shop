<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 07.03.2016
 * Time: 11:50
 */

namespace App\Http\Controllers;


class PublicController extends Controller
{
    public function index()
    {
        return view('public.index');
    }
}