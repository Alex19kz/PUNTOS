<?php

namespace App\Controllers;
use App\Models\cliente;

class ClienteController extends Controller{
    public function index(){
response()->json(['message' => 'entrar al modelo cliente(ddf)']);}
}