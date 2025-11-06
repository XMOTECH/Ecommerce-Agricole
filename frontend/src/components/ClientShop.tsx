import { useState } from 'react';
import { 
  ShoppingCart, 
  Search, 
  User, 
  Heart,
  Star,
  Minus,
  Plus,
  Sprout,
  Filter,
  Menu,
  X,
  Home,
  Grid,
  ShoppingBag,
  Package
} from 'lucide-react';
import { ImageWithFallback } from './figma/ImageWithFallback';
import { Card, CardContent } from './ui/card';
import { Input } from './ui/input';
import { Button } from './ui/button';
import { Badge } from './ui/badge';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from './ui/sheet';
import { ThemeToggle } from './ThemeToggle';

interface Product {
  id: number;
  name: string;
  price: number;
  unit: string;
  image: string;
  producer: string;
  rating: number;
  inStock: boolean;
  category: string;
}

interface CartItem extends Product {
  quantity: number;
}

export function ClientShop() {
  const [cart, setCart] = useState<CartItem[]>([]);
  const [selectedCategory, setSelectedCategory] = useState<string>('Tous');
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isCartOpen, setIsCartOpen] = useState(false);

  const products: Product[] = [
    {
      id: 1,
      name: 'Tomates Cœur de Bœuf',
      price: 4.50,
      unit: 'kg',
      image: 'https://images.unsplash.com/photo-1745791562822-7ac21012bbb2?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxvcmdhbmljJTIwdG9tYXRvZXN8ZW58MXx8fHwxNzYyMjMwNzYwfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
      producer: 'Ferme de la Voliere',
      rating: 4.8,
      inStock: true,
      category: 'Légumes'
    },
    {
      id: 2,
      name: 'Pommes Bio',
      price: 3.20,
      unit: 'kg',
      image: 'https://images.unsplash.com/photo-1623815242959-fb20354f9b8d?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxmcmVzaCUyMGFwcGxlc3xlbnwxfHx8fDE3NjIxODM4NTN8MA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
      producer: "Les Jardins d'Elise",
      rating: 4.9,
      inStock: true,
      category: 'Fruits'
    },
    {
      id: 3,
      name: 'Pommes de Terre Bio',
      price: 2.80,
      unit: 'kg',
      image: 'https://images.unsplash.com/photo-1621460244823-81de69d0eb50?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxmYXJtJTIwcG90YXRvZXN8ZW58MXx8fHwxNzYyMjgwOTExfDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
      producer: 'Ferme de la Voliere',
      rating: 4.7,
      inStock: true,
      category: 'Légumes'
    },
    {
      id: 4,
      name: 'Carottes Fraîches',
      price: 3.50,
      unit: 'kg',
      image: 'https://images.unsplash.com/photo-1598170845058-32b9d6a5da37?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxmcmVzaCUyMGNhcnJvdHN8ZW58MXx8fHwxNzYyMTU0ODk1fDA&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
      producer: "Les Jardins d'Elise",
      rating: 4.6,
      inStock: true,
      category: 'Légumes'
    },
    {
      id: 5,
      name: 'Salade Bio',
      price: 2.20,
      unit: 'pièce',
      image: 'https://images.unsplash.com/photo-1760368104719-3515320cf545?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxvcmdhbmljJTIwc2FsYWQlMjBsZXR0dWNlfGVufDF8fHx8MTc2MjI4MDkxMXww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
      producer: 'Ferme du Soleil',
      rating: 4.5,
      inStock: true,
      category: 'Légumes'
    },
    {
      id: 6,
      name: 'Panier "Le Complet"',
      price: 35.00,
      unit: 'panier',
      image: 'https://images.unsplash.com/photo-1665315302321-46989ca7829a?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=M3w3Nzg4Nzd8MHwxfHNlYXJjaHwxfHxmcmVzaCUyMHZlZ2V0YWJsZXMlMjBmYXJtfGVufDF8fHx8MTc2MjI2Mzk1OXww&ixlib=rb-4.1.0&q=80&w=1080&utm_source=figma&utm_medium=referral',
      producer: 'Ferme de la Voliere',
      rating: 5.0,
      inStock: true,
      category: 'Paniers'
    }
  ];

  const categories = ['Tous', 'Légumes', 'Fruits', 'Paniers'];

  const addToCart = (product: Product) => {
    setCart(prevCart => {
      const existingItem = prevCart.find(item => item.id === product.id);
      if (existingItem) {
        return prevCart.map(item =>
          item.id === product.id
            ? { ...item, quantity: item.quantity + 1 }
            : item
        );
      }
      return [...prevCart, { ...product, quantity: 1 }];
    });
    setIsCartOpen(true);
  };

  const updateQuantity = (productId: number, delta: number) => {
    setCart(prevCart => {
      return prevCart.map(item => {
        if (item.id === productId) {
          const newQuantity = Math.max(0, item.quantity + delta);
          return newQuantity === 0 ? null : { ...item, quantity: newQuantity };
        }
        return item;
      }).filter(Boolean) as CartItem[];
    });
  };

  const getTotalPrice = () => {
    return cart.reduce((total, item) => total + item.price * item.quantity, 0);
  };

  const getTotalItems = () => {
    return cart.reduce((total, item) => total + item.quantity, 0);
  };

  const filteredProducts = selectedCategory === 'Tous' 
    ? products 
    : products.filter(p => p.category === selectedCategory);

  const CartContent = () => (
    <div className="flex flex-col h-full">
      {cart.length === 0 ? (
        <div className="flex-1 flex flex-col items-center justify-center py-12 text-gray-500">
          <ShoppingCart className="w-16 h-16 mb-4 text-gray-300" />
          <p>Votre panier est vide</p>
        </div>
      ) : (
        <>
          <div className="flex-1 overflow-y-auto space-y-4">
            {cart.map(item => (
              <div key={item.id} className="flex gap-3 pb-4 border-b border-gray-200">
                <ImageWithFallback
                  src={item.image}
                  alt={item.name}
                  className="w-20 h-20 rounded-lg object-cover"
                />
                <div className="flex-1 min-w-0">
                  <h4 className="text-sm text-gray-900 mb-1 truncate">{item.name}</h4>
                  <p className="text-xs text-gray-500 mb-2">{item.price.toFixed(2)} € / {item.unit}</p>
                  
                  <div className="flex items-center gap-2">
                    <button
                      onClick={() => updateQuantity(item.id, -1)}
                      className="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors"
                    >
                      <Minus className="w-3 h-3" />
                    </button>
                    <span className="text-sm w-8 text-center">{item.quantity}</span>
                    <button
                      onClick={() => updateQuantity(item.id, 1)}
                      className="w-7 h-7 rounded-lg bg-gray-100 hover:bg-gray-200 flex items-center justify-center transition-colors"
                    >
                      <Plus className="w-3 h-3" />
                    </button>
                  </div>
                </div>
                <div className="text-sm text-gray-900">
                  {(item.price * item.quantity).toFixed(2)} €
                </div>
              </div>
            ))}
          </div>

          <div className="border-t border-gray-200 pt-4 mt-4 space-y-4">
            <div className="space-y-2">
              <div className="flex justify-between text-sm">
                <span className="text-gray-600">Sous-total</span>
                <span className="text-gray-900">{getTotalPrice().toFixed(2)} €</span>
              </div>
              <div className="flex justify-between text-sm">
                <span className="text-gray-600">Livraison</span>
                <span className="text-green-600">Gratuite</span>
              </div>
              <div className="border-t border-gray-200 pt-2 flex justify-between">
                <span className="text-gray-900">Total</span>
                <span className="text-[#1e4d3d]">{getTotalPrice().toFixed(2)} €</span>
              </div>
            </div>

            <Button className="w-full bg-[#1e4d3d] hover:bg-[#153a2d] text-white h-12">
              Commander
            </Button>

            <p className="text-xs text-gray-500 text-center">
              Livraison gratuite pour toute commande supérieure à 30€
            </p>
          </div>
        </>
      )}
    </div>
  );

  return (
    <div className="min-h-screen bg-background pb-20 lg:pb-0">
      {/* Header */}
      <header className="bg-[#1e4d3d] dark:bg-[#153a2d] text-white sticky top-0 z-40 shadow-lg">
        <div className="px-4 lg:px-6 py-4">
          <div className="flex items-center justify-between gap-4">
            {/* Logo and Menu Toggle */}
            <div className="flex items-center gap-3">
              <button
                onClick={() => setIsMenuOpen(!isMenuOpen)}
                className="lg:hidden p-2 hover:bg-[#2a5f4a] rounded-lg transition-colors"
              >
                {isMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
              </button>
              
              <div className="flex items-center gap-2">
                <div className="w-10 h-10 bg-[#2a5f4a] rounded-lg flex items-center justify-center">
                  <Sprout className="w-6 h-6" />
                </div>
                <span className="hidden sm:block">AgriMarket</span>
              </div>
            </div>

            {/* Search Bar - Desktop */}
            <div className="hidden lg:flex flex-1 max-w-xl">
              <div className="relative w-full">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
                <Input 
                  placeholder="Rechercher des produits..." 
                  className="pl-10 bg-white border-0 h-11"
                />
              </div>
            </div>

            {/* Actions */}
            <div className="flex items-center gap-2 lg:gap-3">
              {/* Theme Toggle */}
              <ThemeToggle className="text-white hover:bg-[#2a5f4a]" />
              
              <button className="hidden lg:flex items-center gap-2 p-2 hover:bg-[#2a5f4a] rounded-lg transition-colors">
                <Heart className="w-5 h-5" />
                <span className="text-sm">Favoris</span>
              </button>
              
              {/* Cart - Desktop */}
              <Sheet open={isCartOpen} onOpenChange={setIsCartOpen}>
                <SheetTrigger asChild>
                  <button className="hidden lg:flex items-center gap-2 relative p-2 hover:bg-[#2a5f4a] rounded-lg transition-colors">
                    <ShoppingCart className="w-5 h-5" />
                    <span className="text-sm">Panier</span>
                    {getTotalItems() > 0 && (
                      <div className="absolute top-0 right-0 bg-[#ff6b35] text-white text-xs w-5 h-5 rounded-full flex items-center justify-center animate-in fade-in zoom-in">
                        {getTotalItems()}
                      </div>
                    )}
                  </button>
                </SheetTrigger>
                <SheetContent className="w-full sm:max-w-md">
                  <SheetHeader className="mb-6">
                    <SheetTitle className="flex items-center gap-2">
                      <ShoppingCart className="w-5 h-5" />
                      Mon Panier
                    </SheetTitle>
                  </SheetHeader>
                  <CartContent />
                </SheetContent>
              </Sheet>

              {/* User Menu */}
              <button className="hidden lg:flex items-center gap-2 p-2 hover:bg-[#2a5f4a] rounded-lg transition-colors">
                <User className="w-5 h-5" />
                <span className="text-sm">Mon Compte</span>
              </button>
            </div>
          </div>

          {/* Search Bar - Mobile */}
          <div className="lg:hidden mt-3">
            <div className="relative w-full">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" />
              <Input 
                placeholder="Rechercher..." 
                className="pl-10 bg-white border-0 h-11"
              />
            </div>
          </div>

          {/* Categories - Desktop */}
          <div className="hidden lg:block border-t border-[#2a5f4a] mt-4 pt-3">
            <div className="flex items-center gap-6">
              <button className="flex items-center gap-2 text-sm hover:text-gray-200 transition-colors">
                <Filter className="w-4 h-4" />
                Filtres
              </button>
              {categories.map(category => (
                <button
                  key={category}
                  onClick={() => setSelectedCategory(category)}
                  className={`text-sm transition-colors ${
                    selectedCategory === category
                      ? 'text-[#ff6b35]'
                      : 'text-gray-200 hover:text-white'
                  }`}
                >
                  {category}
                </button>
              ))}
            </div>
          </div>
        </div>

        {/* Mobile Menu Dropdown */}
        {isMenuOpen && (
          <div className="lg:hidden border-t border-[#2a5f4a] animate-in slide-in-from-top duration-200">
            <div className="px-4 py-3 space-y-2">
              {categories.map(category => (
                <button
                  key={category}
                  onClick={() => {
                    setSelectedCategory(category);
                    setIsMenuOpen(false);
                  }}
                  className={`w-full text-left px-4 py-2 rounded-lg transition-colors ${
                    selectedCategory === category
                      ? 'bg-[#ff6b35] text-white'
                      : 'text-gray-200 hover:bg-[#2a5f4a]'
                  }`}
                >
                  {category}
                </button>
              ))}
            </div>
          </div>
        )}
      </header>

      {/* Main Content */}
      <div className="max-w-7xl mx-auto px-4 lg:px-6 py-6 lg:py-8">
        {/* Page Title */}
        <div className="mb-6">
          <h2 className="text-gray-900">Produits Frais de nos Producteurs</h2>
          <p className="text-gray-600 mt-1">
            {filteredProducts.length} produit{filteredProducts.length > 1 ? 's' : ''} disponible{filteredProducts.length > 1 ? 's' : ''}
          </p>
        </div>

        {/* Products Grid */}
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 lg:gap-6">
          {filteredProducts.map(product => (
            <Card key={product.id} className="overflow-hidden hover:shadow-xl transition-all duration-300 group">
              <div className="relative overflow-hidden">
                <ImageWithFallback
                  src={product.image}
                  alt={product.name}
                  className="w-full h-48 lg:h-56 object-cover group-hover:scale-105 transition-transform duration-300"
                />
                <div className="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity" />
                
                {product.inStock ? (
                  <Badge className="absolute top-3 right-3 bg-green-500 hover:bg-green-600 shadow-lg">
                    En stock
                  </Badge>
                ) : (
                  <Badge className="absolute top-3 right-3 bg-red-500 hover:bg-red-600 shadow-lg">
                    Épuisé
                  </Badge>
                )}
                <button className="absolute top-3 left-3 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white hover:scale-110 transition-all shadow-md">
                  <Heart className="w-4 h-4 text-gray-600" />
                </button>
              </div>
              
              <CardContent className="p-4">
                <div className="mb-2">
                  <h3 className="text-gray-900 mb-1 truncate">{product.name}</h3>
                  <p className="text-xs text-gray-500 truncate">{product.producer}</p>
                </div>

                <div className="flex items-center gap-1 mb-3">
                  <Star className="w-4 h-4 fill-[#ff6b35] text-[#ff6b35]" />
                  <span className="text-sm text-gray-700">{product.rating}</span>
                </div>

                <div className="flex items-center justify-between">
                  <div>
                    <span className="text-[#1e4d3d]">{product.price.toFixed(2)} €</span>
                    <span className="text-sm text-gray-500 ml-1">/ {product.unit}</span>
                  </div>
                  
                  <Button
                    onClick={() => addToCart(product)}
                    disabled={!product.inStock}
                    className="bg-[#ff6b35] hover:bg-[#e55a28] text-white shadow-md hover:shadow-lg transition-all"
                    size="sm"
                  >
                    <Plus className="w-4 h-4" />
                  </Button>
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      </div>

      {/* Mobile Bottom Navigation */}
      <div className="lg:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-40">
        <div className="grid grid-cols-4 gap-1">
          <button className="flex flex-col items-center justify-center py-3 text-[#1e4d3d] bg-[#f0f9f4]">
            <Home className="w-5 h-5 mb-1" />
            <span className="text-xs">Accueil</span>
          </button>
          
          <button className="flex flex-col items-center justify-center py-3 text-gray-600 hover:text-[#1e4d3d] hover:bg-gray-50 transition-colors">
            <Grid className="w-5 h-5 mb-1" />
            <span className="text-xs">Catégories</span>
          </button>
          
          <Sheet open={isCartOpen} onOpenChange={setIsCartOpen}>
            <SheetTrigger asChild>
              <button className="flex flex-col items-center justify-center py-3 text-gray-600 hover:text-[#1e4d3d] hover:bg-gray-50 transition-colors relative">
                <ShoppingBag className="w-5 h-5 mb-1" />
                <span className="text-xs">Panier</span>
                {getTotalItems() > 0 && (
                  <div className="absolute top-1 right-1/4 bg-[#ff6b35] text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                    {getTotalItems()}
                  </div>
                )}
              </button>
            </SheetTrigger>
            <SheetContent side="bottom" className="h-[90vh]">
              <SheetHeader className="mb-6">
                <SheetTitle className="flex items-center gap-2">
                  <ShoppingCart className="w-5 h-5" />
                  Mon Panier
                </SheetTitle>
              </SheetHeader>
              <CartContent />
            </SheetContent>
          </Sheet>
          
          <button className="flex flex-col items-center justify-center py-3 text-gray-600 hover:text-[#1e4d3d] hover:bg-gray-50 transition-colors">
            <User className="w-5 h-5 mb-1" />
            <span className="text-xs">Compte</span>
          </button>
        </div>
      </div>
    </div>
  );
}
