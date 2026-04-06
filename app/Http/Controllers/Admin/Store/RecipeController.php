<?php

namespace App\Http\Controllers\Admin\Store;

use App\Http\Controllers\Controller;
use App\Http\Requests\Store\RecipeFormRequest;
use App\Models\Store\Recipe;
use App\Repositories\Store\Ingredient\IngredientRepositoryContract;
use App\Repositories\Store\Recipe\RecipeRepositoryContract;
use App\Transformers\RecipeTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Yajra\DataTables\Facades\DataTables;

class RecipeController extends Controller
{
    public function __construct(
        protected RecipeRepositoryContract $recipes,
        protected IngredientRepositoryContract $ingredients
    ) {
    }

    public function index(): InertiaResponse
    {
        return Inertia::render('Admin/Store/Recipes/Index', [
            'recipes' => Recipe::select(['id', 'name', 'sku', 'active', 'created_at'])
                ->orderBy('name')
                ->get()
                ->map(fn ($r) => [
                    'id' => $r->id,
                    'name' => $r->name,
                    'sku' => $r->sku,
                    'active' => (bool) $r->active,
                ])
                ->values(),
        ]);
    }

    public function dataTables()
    {
        $recipes = Recipe::select(['id', 'name', 'active', 'created_at', 'updated_at']);

        return DataTables::of($recipes)
            ->setTransformer(new RecipeTransformer())
            ->make(true);
    }

    public function create(): InertiaResponse
    {
        $ingredients = $this->ingredients->getAll();

        return Inertia::render('Admin/Store/Recipes/Create', [
            'ingredients' => collect($ingredients)->map(fn ($i) => [
                'id' => $i->id,
                'name' => $i->name,
            ])->values(),
        ]);
    }

    public function store(RecipeFormRequest $request): RedirectResponse
    {
        $this->recipes->create($request->all());

        return redirect('/admin/store/recipes');
    }

    public function show(int $id): InertiaResponse
    {
        $recipe = $this->recipes->findById($id);
        $ingredients = $this->ingredients->getAll();

        return Inertia::render('Admin/Store/Recipes/Show', [
            'recipe' => [
                'id' => $recipe->id,
                'name' => $recipe->name,
                'sku' => $recipe->sku,
                'active' => (bool) $recipe->active,
                'ingredients' => $recipe->ingredients->map(fn ($i) => [
                    'id' => $i->id,
                    'name' => $i->name,
                    'amount' => $i->pivot->amount ?? 0,
                ])->values(),
            ],
            'availableIngredients' => collect($ingredients)->map(fn ($i) => [
                'id' => $i->id,
                'name' => $i->name,
            ])->values(),
        ]);
    }

    public function ajaxUpdate(int $id, RecipeFormRequest $request): JsonResponse
    {
        $status = $this->recipes->updateField($id, $request->get('name'), $request->get('value')) ? 1 : 0;

        return response()->json(['status' => $status]);
    }

    public function update(int $id, Request $request): RedirectResponse
    {
        $this->recipes->updateField($id, 'active', $request->get('active'));

        return back();
    }

    public function remove(int $id, int $iid): RedirectResponse
    {
        $this->recipes->removeIngredient($id, $iid);

        return back();
    }

    public function add(Request $request): RedirectResponse
    {
        $recipe = $this->recipes->findById($request->get('recipe'));
        $this->recipes->addIngredient($recipe, $request->all());

        return back();
    }
}
