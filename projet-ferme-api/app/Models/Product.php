<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
    //
    protected $fillable = [
        'category_id',
        'nom',
        'description',
        'prix_base',
        'prix_etudiant',
        'stock',
        'statut',
    ];
    public function category(){
        return $this->belongsTo(Category::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'produit_id');
    }
}
