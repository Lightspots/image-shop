<?php
/**
 * Created by PhpStorm.
 * User: benjamin
 * Date: 20.03.2016
 * Time: 12:06
 */

namespace App\Http\Controllers;


class AdmController extends Controller
{
    public function index()
    {
        return view('adm.index');
    }
}