<?php

namespace App\Http\Controllers\Blog\Admin;

//use App\Http\Controllers\Blog\Admin\BaseController;
use App\Models\BlogCategory;
use App\Repositories\BlogCategoryRepository;
use Illuminate\Support\Str;
//use Illuminate\Http\Request;
use App\Http\Requests\BlogCategoryUpdateRequest;
use App\Http\Requests\BlogCategoryCreateRequest;


class CategoryController extends BaseController
{
    /**
     * @var BlogCategoryRepository
     */
    private $blogCategoryRepository;

    public function __construct()
    {
        parent::__construct();
        $this->blogCategoryRepository = app(BlogCategoryRepository::class);
    }
    public function index()
    {
        //dd(__METHOD__);

        //$paginator = BlogCategory::paginate(5);
        $paginator = $this->blogCategoryRepository->getAllWithPaginate(5);
        return view('blog.admin.categories.index', compact('paginator'));

    }

    public function create()
    {
        //dd(__METHOD__);
        $item = new BlogCategory();
        $categoryList = //BlogCategory::all()
            $this->blogCategoryRepository->getForComboBox();
        ;

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }

    public function store(BlogCategoryCreateRequest $request)
    {
        //dd(__METHOD__);
        $data = $request->input(); //отримаємо масив даних, які надійшли з форми
        if (empty($data['slug'])) { //якщо псевдонім порожній
            $data['slug'] = Str::slug($data['title']); //генеруємо псевдонім
        }

        $item = (new BlogCategory())->create($data); //створюємо об'єкт і додаємо в БД

        if ($item) {
            return redirect()
                ->route('blog.admin.categories.edit', [$item->id])
                ->with(['success' => 'Успішно збережено']);
        } else {
            return back()
                ->withErrors(['msg' => 'Помилка збереження'])
                ->withInput();
        }
    }

    public function show(string $id)
    {
        //dd(__METHOD__);
    }

    public function edit($id)
    {
        $item = $this->blogCategoryRepository->getEdit($id);
        if (empty($item)) {                         //помилка, якщо репозиторій не знайде наш ід
            abort(404);
        }
        $categoryList = $this->blogCategoryRepository->getForComboBox($item->parent_id);

        return view('blog.admin.categories.edit', compact('item', 'categoryList'));
    }


    public function update(BlogCategoryUpdateRequest $request, $id)
    {
        //dd(__METHOD__);
        $item = $this->blogCategoryRepository->getEdit($id);
        ;
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
