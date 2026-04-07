<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\IngredientFormRequest;
use App\Repositories\Store\Ingredient\IngredientRepositoryContract;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class IngredientController extends Controller
{
    public function __construct(protected IngredientRepositoryContract $ingredients)
    {
    }

    public function index(): InertiaResponse
    {
        $ingredients = $this->ingredients->getAll();

        return Inertia::render('Admin/Store/Ingredients/Index', [
            'ingredients' => collect($ingredients)->map(fn ($i) => [
                'id' => $i->id,
                'name' => $i->name,
                'vendor' => $i->vendor,
            ])->values(),
        ]);
    }

    public function store(IngredientFormRequest $request): RedirectResponse
    {
        $this->ingredients->create($request->all());

        return redirect('/admin/store/ingredients');
    }

    public function edit(int $id): InertiaResponse
    {
        $ingredient = $this->ingredients->findById($id);

        return Inertia::render('Admin/Store/Ingredients/Edit', [
            'ingredient' => [
                'id' => $ingredient->id,
                'name' => $ingredient->name,
                'vendor' => $ingredient->vendor,
            ],
        ]);
    }

    public function update(int $id, IngredientFormRequest $request): RedirectResponse
    {
        $this->ingredients->update($id, $request->all());

        return redirect('/admin/store/ingredients');
    }
}
