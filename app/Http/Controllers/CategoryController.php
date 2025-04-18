<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Categories;
use App\Models\Blog;
use App\Models\Product;


class CategoryController extends Controller
{
    public function index() {

        // $categories = Categories::where('status', 'active')->get();
        $categories = Categories::where('status', 'active')
        ->where('sorting', '!=', '0') // Replace $someValue with the value you want to exclude
        ->orderBy('sorting', 'asc') // Order by sorting in ascending order
        ->get();


        return view('layouts.header' , compact('categories'));

    }
    public function timmer(){
        return view('timer');

    }
    public function halo () {
        return view('halloweeni');
    }
    public function show($id)
    {
        // $categoryModel = new Categories();
        // // $category = $categoryModel->find($id);
        // $category = $categoryModel->where('title', $id)->first();

        $slugTitle = Str::slug($id); // Generate slug version of title
        $category = Categories::whereRaw("REPLACE(title, ' ', '-') = ?", [$slugTitle])->first();


        if (!$category) {
            return redirect()->back()->withErrors('Category not found.');
        }

        
        $productModel = new Product();
        $query = $productModel->select('id', 'title', 'image_1') // Select specific columns
        ->where('status', 'active'); // Common condition
    
        if ($category->nav_id == '1') {
            $query->where('industry', $category->id);
        } elseif ($category->nav_id == '2') {
            $query->where('box', $category->id);
        } elseif ($category->nav_id == '3') {
            $query->where('category_id', $category->id);
        }
        
        $products = $query->get()->toArray();
        // echo $products->toSql();


        return view('listof', compact('category', 'products'));
    }
    public function final($id){

        // $id = $request['id'];
        $slugTitle = Str::slug($id); // Generate slug version of title
        $products = Product::whereRaw("REPLACE(title, ' ', '-') = ?", [$slugTitle])->first();
        // $productModel = new Product();
        // $products = $productModel->where('id',$id)->first();

        if (!$products) {
            return redirect()->back()->withErrors('Category not found.');
        }

        return view('finalp', compact('products'));
    }
    public function team() {
        return view('aboutteam');
    }
    public function thankyou() {
        return view('thanku');
    }
    public function blogblog(){
        $blog = blog::select('id', 'title' , 'main_img')->get();

        return view('blog', compact('blog'));
    }
    
    
    public function blogblogdetails($id){

        // $id = $request['id'];
        $blogModel = new blog();
        $blog = $blogModel->where('id',$id)->first();

        if (!$blog) {
            return redirect()->back()->withErrors('Blog not found.');
        }

        return view('blogdetails', compact('blog'));
    }
}
