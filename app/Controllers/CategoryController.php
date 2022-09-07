<?php

namespace App\Controllers;

use App\Core\Application;
use App\Core\View;
use App\Models\Category;

class CategoryController
{
    /**
     * Show list of categories
     */
    public function index(Application $app): View
    {
        $search = $app->request()->input('search', '');
        $currentPage = $app->request()->input('page', 1);
        $countCategories = Category::count('WHERE name LIKE ? AND parent_id IS NULL', ['%'.$search.'%']);
        $perPage = 5;
        $totalPage = ceil($countCategories / $perPage);

        $categories = Category::paginateWithDeepChildrenAndSearch($currentPage, $perPage, $search);

        return new View('category.index', [
            'currentPage' => $currentPage,
            'totalPage' => $totalPage,
            'categories' => $categories,
            'allCategories' => Category::select(),
        ]);
    }

    /**
     * Store a new category
     */
    public function store(Application $app): View
    {
        $name = $app->request()->input('name');
        $parentId = $app->request()->input('parent_id');

        $category = new Category();
        $category->name = $name;
        $category->parent_id = $parentId;
        $category->save();

        // redirect back to index page
        header('Location: /');
        exit;
    }

    /**
     * Update a category
     */
    public function update(Application $app): View
    {
        $id = $app->request()->input('id');
        $name = $app->request()->input('name');
        $parentId = $app->request()->input('parent_id');

        $category = Category::find($id);

        $category->name = $name;
        $category->parent_id = $parentId;
        $category->save();

        // redirect back to index page
        header('Location: /');
        exit;
    }

    /**
    * Delete a category
    */
    // public function delete(Application $app): View
    // {
    // }
}
