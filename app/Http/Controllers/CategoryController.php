<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories']);
        Category::create($request->all());
        return back()->with('success', 'New Category added!');
    }

    public function update(Request $request, $id)
    {
        $category = \App\Models\Category::findOrFail($id);
        $category->update($request->validate([
            'name' => 'required|string|max:255'
        ]));

        return back()->with('success', 'Category updated!');
    }

    public function destroy($id)
    {
        $category = \App\Models\Category::findOrFail($id);
        $category->delete();

        return back()->with('success', 'Category deleted!');
    }
}
