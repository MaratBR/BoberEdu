<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;

interface IResourceController
{
    public function show(Request $request);
    public function store(Request $request);
    public function index(Request $request);
    public function update(Request $request);
}
