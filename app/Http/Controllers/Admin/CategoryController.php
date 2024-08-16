<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Nette\Schema\ValidationException;
use function redirect;
use function view;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::paginate(10);
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.categories.add', compact('categories'));

    }

    /**
     * Store a newly created resource in storage.
     */
public function store(CategoryRequest $request)
{
    try {
        Category::create($request->validated());
        return redirect()->route('admin.categories.index')
            ->with('success', 'تم إنشاء الكاتيجوري بنجاح');

    } catch (Exception $e) {
        return redirect()->back()
            ->withErrors($e->errors())
            ->withInput();
    }
}
    /**
     * Display the specified resource.
     */
public function show(Category $category)
{
    $category->load('children', 'parent');
    $breadcrumbs = $this->getBreadcrumbs($category);
    return view('admin.categories.show', compact('category', 'breadcrumbs'));
}

private function getBreadcrumbs(Category $category)
{
    $breadcrumbs = collect([$category]);
    $parent = $category->parent;
    while($parent) {
        $breadcrumbs->prepend($parent);
        $parent = $parent->parent;
    }
    return $breadcrumbs;
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)->get();
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    public function update(CategoryRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());
            return redirect()->route('admin.categories.index')
                ->with('success', 'تم تحديث الكاتيجوري بنجاح');

        } catch (Exception $e) {
            return redirect()->back()
                ->withErrors($e->errors())
                ->withInput();
        }
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();
            return redirect()->route('admin.categories.index')
                ->with('success', 'تم حذف الكاتيجوري بنجاح');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء محاولة حذف الكاتيجوري');
        }
    }

}
