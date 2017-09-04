<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;
use App\SectionTitles;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $language = SectionTitles::findOrFail(1);
        $categories = Category::all();
        return view('admin.categorylist',compact('categories','language'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categoryadd');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'slug' => 'required|max:255|unique:category',
        ]);

        if ($validator->passes()) {
            $category = new Category;
            $category->fill($request->all());
            $category->save();
            Session::flash('message', 'New Category Added Successfully.');
            return redirect('admin/category');
        }
        return redirect()->back()->with('message', 'Category Slug Must Be Unique.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function titles(Request $request)
    {
        $service = SectionTitles::findOrFail(1);
        $data['category_title'] = $request->category_title;
        $data['category_text'] = $request->category_text;
        $service->update($data);
        return redirect('admin/category')->with('message','Category Section Title & Text Updated Successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categoryedit',compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(),[
            'slug' => 'required|max:255|unique:category,slug,'.$id,
        ]);

        if ($validator->passes()) {
            $category = Category::findOrFail($id);
            $input = $request->all();
            $category->update($input);
            Session::flash('message', 'Category Updated Successfully.');
            return redirect('admin/category');
        }
        return redirect()->back()->with('message', 'Category Slug Must Be Unique.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        Session::flash('message', 'Category Deleted Successfully.');
        return redirect('admin/category');
    }
}
