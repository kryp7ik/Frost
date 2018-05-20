<?php

namespace App\Http\Controllers\Admin\Store;

use App\Models\Store\Recipe;
use App\Repositories\Store\Ingredient\IngredientRepositoryContract;
use App\Repositories\Store\Recipe\RecipeRepositoryContract;
use App\Transformers\RecipeTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Store\RecipeFormRequest;
use Yajra\Datatables\Facades\Datatables;

class RecipeController extends Controller
{
    /**
     * @var RecipeRepositoryContract
     */
    protected $recipes;

    /**
     * @var IngredientRepositoryContract
     */
    protected $ingredients;

    /**
     * RecipeController constructor.
     * @param RecipeRepositoryContract $recipes
     * @param IngredientRepositoryContract $ingredients
     */
    public function __construct(RecipeRepositoryContract $recipes, IngredientRepositoryContract $ingredients)
    {
        $this->recipes = $recipes;
        $this->ingredients = $ingredients;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('backend.store.recipes.index');
    }

    public function dataTables()
    {
        $recipes = Recipe::select(['id', 'name', 'active', 'created_at', 'updated_at']);
        return Datatables::of($recipes)
            ->setTransformer(new RecipeTransformer())
            ->setRowId(function($recipe) { return $recipe->id; })
            ->make(true);
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $ingredients = $this->ingredients->getAll();
        return view('backend.store.recipes.create', compact('ingredients'));
    }

    /**
     * @param RecipeFormRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(RecipeFormRequest $request)
    {
        $this->recipes->create($request->all());
        return redirect('/admin/store/recipes');
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $recipe = $this->recipes->findById($id);
        $ingredients = $this->ingredients->getAll();
        return view('backend.store.recipes.show', compact('recipe', 'ingredients'));
    }

    /**
     * @param int $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        $recipe = $this->recipes->findById($id);
        return view('backend.store.recipes.edit', compact('recipe'));
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function editUpdate($id, Request $request)
    {
        $recipe = $this->recipes->findById($id);
        $recipe->fill($request->all());
        $recipe->save();
        return redirect('/admin/store/recipes');
    }

    /**
     * @param int $id
     * @param RecipeFormRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajaxUpdate($id, RecipeFormRequest $request)
    {
        if($this->recipes->updateField($id, $request->get('name'), $request->get('value'))) {
            return response()->json(array('status' => 1));
        } else {
            return response()->json(array('status' => 0));
        }
    }

    /**
     * @param int $id
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update($id, Request $request)
    {
        $this->recipes->updateField($id, 'active', $request->get('active'));
        return back();
    }

    /**
     * Removes a single ingredient from a recipe
     * @param int $id The ID of the recipe being modified
     * @param int $iid The ID of the ingredient being removed
     * @return \Illuminate\Http\RedirectResponse
     */
    public function remove($id, $iid)
    {
        $this->recipes->removeIngredient($id, $iid);
        return back();
    }

    /**
     * Adds a single ingredient to a recipe.
     * The recipe id is defined in a hidden form input 'recipe'.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function add(Request $request)
    {
        $recipe = $this->recipes->findById($request->get('recipe'));
        $this->recipes->addIngredient($recipe, $request->all());
        return back();
    }
}
