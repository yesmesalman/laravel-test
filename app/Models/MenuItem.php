<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    // task 3
    public function children()
    {
        return $this->hasMany(MenuItem::class, 'parent_id')->with('children');
    }
}
