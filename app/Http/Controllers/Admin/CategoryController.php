<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\CategoryRequest;
use App\Models\Admin\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Nette\Schema\ValidationException;
use function redirect;
use function view;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CategoryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [

            new Middleware('checkRole:superAdmin', only: ['destroy']),
        ];
    }
    public function index()
    {
        $categories = Category::paginate(get_pagination_count());
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

                // الحصول على امتداد الصورة
                $extension = $request->file('image')->getClientOriginalExtension();

                // إنشاء اسم جديد للصورة مع ضمان أن يكون فريدًا
                $imageName = time() . '_' . uniqid() . '.' . $extension;

                // رفع الصورة مع الاسم الجديد إلى مجلد categories
                $imagePath = $request->file('image')->storeAs('categories', $imageName, 'public');


            // إنشاء الكاتيجوري مع الصورة
            Category::create([
                'name' => $request->name,
                'description' => $request->description,
                'parent_id' => $request->parent_id,
                'image' => $imagePath, // تخزين مسار الصورة
            ]);

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
            // التحقق مما إذا كانت هناك صورة جديدة
            if ($request->hasFile('image')) {
                // حذف الصورة القديمة إذا كانت موجودة
                if ($category->image && Storage::disk('public')->exists($category->image)) {
                    Storage::disk('public')->delete($category->image);
                }

                // رفع الصورة الجديدة
                $extension = $request->file('image')->getClientOriginalExtension();
                $imageName = time() . '_' . uniqid() . '.' . $extension;
                $imagePath = $request->file('image')->storeAs('categories', $imageName, 'public');

                // تحديث باقي البيانات
                $category->update([
                    'name'=>$request->name,
                    'description'=>$request->description,
                    'parent_id'=>$request->parent_id,
                    'image'=>$imagePath,
                ]);
            }else{
                // تحديث باقي البيانات
                $category->update([
                    'name'=>$request->name,
                    'description'=>$request->description,
                    'parent_id'=>$request->parent_id,
                ]);
            }



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
            // التحقق مما إذا كانت الصورة موجودة
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                // حذف الصورة من السيرفر
                Storage::disk('public')->delete($category->image);
            }

            // حذف القسم
            $category->delete();

            return redirect()->route('admin.categories.index')
                ->with('success', 'تم حذف الكاتيجوري بنجاح');
        } catch (Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء محاولة حذف الكاتيجوري');
        }
    }

}
