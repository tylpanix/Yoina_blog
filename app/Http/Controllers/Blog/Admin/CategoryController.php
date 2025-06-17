<?php

namespace App\Http\Controllers\Blog\Admin;

//use App\Http\Controllers\Blog\Admin\BaseController;
use App\Models\BlogCategory;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    public function index()
    {
        //dd(__METHOD__);
        $paginator = BlogCategory::paginate(5);

        return view('blog.admin.categories.index', compact('paginator'));

    }

    public function create()
    {
        //dd(__METHOD__);
    }

    public function store(Request $request)
    {
        //dd(__METHOD__);
    }

    public function show(string $id)
    {
        //dd(__METHOD__);
    }

    public function edit(string $id)
    {
        //dd(__METHOD__);
        $item = BlogCategory::findOrFail($id);
        $categoryList = BlogCategory::all();

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));

    }

    public function update(Request $request, string $id)
    {
        //dd(__METHOD__);
        $item = BlogCategory::find($id);
        if (empty($item)) { //якщо ід не знайдено
            return back() //redirect back
            ->withErrors(['msg' => "Запис id=[{$id}] не знайдено"]) //видати помилку
            ->withInput(); //повернути дані
        }

        $data = $request->all(); //отримаємо масив даних, які надійшли з форми
        if (empty($data['slug'])) { //якщо псевдонім порожній
            $data['slug'] = Str::slug($data['title']); //генеруємо псевдонім
        }

        $result = $item->update($data);  //оновлюємо дані об'єкта і зберігаємо в БД

        if ($result) {
            return redirect()
                ->route('blog.admin.categories.edit', $item->id)
                ->with(['success' => 'Успішно збережено']);
        } else {
            return back()
                ->with(['msg' => 'Помилка збереження'])
                ->withInput();
        }
    }

    public function destroy(string $id)
    {
        //dd(__METHOD__);
    }
}
