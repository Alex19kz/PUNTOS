<?php

app()->get('/', function () {
    response()->json(['message' => 'Congrats!! You\'re on Leaf API hola xd']);
});

app()->get('/cliente', 'ClienteController@index');

app()->get('/app', function () {
    response()->json(app()->routers());
});
