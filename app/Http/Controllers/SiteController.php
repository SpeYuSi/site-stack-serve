<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Category;
use App\Site;
use Illuminate\Routing\Controller as BaseController;

class SiteController extends BaseController
{
  public function allSites() {
    try {
      $sites = Category::with(['children' => function ($query) {
        $query->orderBy('order');
      }, 'sites'])
        ->withCount('children')
        ->orderBy('order')
        ->get();
    } catch (\Exception $exception) {
      return response($exception->getMessage(), 500);
    }
    return response()->json($sites);
  }

  public function allCategory() {
    $categories = Category::get();
    return response()->json($categories);
  }

  public function addSites(Request $request) {
    $query = $request->input();
    $site = new Site();
    $site->category_id = $query['category_id'];
    $site->title = $query['title'];
    $site->thumb = $query['thumb'];
    $site->url = $query['url'];
    $site->describe = $query['describe'];
    try {
      DB::beginTransaction();
      $site->save();
      DB::commit();
    } catch (\Exception $exception) {
      DB::rollBack();
      return response($exception->getMessage(), 500);
    }
    return response('Add site success!');
  }

  public function addCategory(Request $request) {
    $query = $request->input();
    $category = new Category();
    $category->parent_id = $query['parent_id'];
    $category->order = $query['order'];
    $category->title = $query['title'];
    try {
      DB::beginTransaction();
      $category->save();
      DB::commit();
    } catch (\Exception $exception) {
      DB::rollBack();
      return response($exception->getMessage(), 500);
    }
    return response('Add category success!');
  }
}
