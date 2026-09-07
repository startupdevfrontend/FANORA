<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\AuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(protected AuditService $audit)
    {
    }

    public function index(): View
    {
        $categories = Category::withCount('creators')->orderBy('name')->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']).'-'.Str::lower(Str::random(4)),
            'description' => $validated['description'] ?? null,
            'is_active' => true,
        ]);

        $this->audit->log(auth()->user(), 'category.created', $category);

        return back()->with('status', 'Categoria criada.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:500'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $old = $category->only(['name', 'description', 'is_active']);

        $category->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        $this->audit->log(auth()->user(), 'category.updated', $category, $old, $category->only(['name', 'description', 'is_active']));

        return back()->with('status', 'Categoria atualizada.');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $category->creators()->detach();
        $category->delete();

        $this->audit->log(auth()->user(), 'category.deleted', $category);

        return back()->with('status', 'Categoria excluída.');
    }
}