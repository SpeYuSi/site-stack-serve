<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
  public function children()
  {
    return $this->hasMany(static::class, 'parent_id');
  }

  public function sites()
  {
    return $this->hasMany(Site::class);
  }
}
