<?php

class LogoutController extends Controller
{
//    public function index()
//    {
//        Session::delete('login');
//        Session::delete('role');
//        Router::redirect('/signin');
//    }

    public function admin_index()
    {
        Session::delete('login');
        Session::delete('role');
        Router::redirect('/admin');
    }


}