<?php

namespace App\Http\Controllers;
use Illuminate\View\View;
use App\Models\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(): View{
        $allCategories = Category::all();
        return view('categories.index')->with('categories',$allCategories);
    }
}
