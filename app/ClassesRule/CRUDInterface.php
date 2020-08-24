<?php

namespace App\ClassesRule;

use Illuminate\Http\Request;

interface CRUDInterface {
    public function index();
    public function add();
    public function update($id);
    public function update_process(Request $req,$id);
    public function add_process(Request $req);
}
