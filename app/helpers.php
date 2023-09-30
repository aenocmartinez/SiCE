<?php

use Illuminate\Routing\Route;

function setActive($routeName) {
    return request()->routeIs($routeName) ? 'active' : '';}