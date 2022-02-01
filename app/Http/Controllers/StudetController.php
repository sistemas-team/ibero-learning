<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ManageCart;
use App\Traits\Student\ManageCourses;
use App\Traits\Student\ManageOrders;

class StudetController extends Controller
{
    use ManageCart, ManageCourses, ManageOrders;

    public function index()
    {
        return view('student.index');
    }
}
