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
  Package,
  Bell,
  Settings,
  LogOut,
  UserCircle,
  ChevronDown,
  Trash2,
  ArrowRight,
  Tag,
  Percent
} from 'lucide-react';
import { ImageWithFallback } from './figma/ImageWithFallback';
import { Card, CardContent } from './ui/card';
import { Input } from './ui/input';
import { Button } from './ui/button';
import { Badge } from './ui/badge';
import { Sheet, SheetContent, SheetHeader, SheetTitle, SheetTrigger } from './ui/sheet';
import { ThemeToggle } from './ThemeToggle';
import { 
  DropdownMenu, 
  DropdownMenuContent, 
  DropdownMenuItem, 
  DropdownMenuLabel, 
  DropdownMenuSeparator, 
  DropdownMenuTrigger 
} from './ui/dropdown-menu';
import { Avatar, AvatarFallback } from './ui/avatar';

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

interface ClientShopProps {
  onLogout: () => void;
}

export function ClientShop({ onLogout }: ClientShopProps) {
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

  const removeFromCart = (productId: number) => {
    setCart(prevCart => prevCart.filter(item => item.id !== productId));
  };

  const clearCart = () => {
    setCart([]);
  };

  const CartContent = () => {
    const subtotal = getTotalPrice();
    const shippingCost = subtotal >= 30 ? 0 : 4.99;
    const total = subtotal + shippingCost;
    const savings = subtotal >= 30 ? 4.99 : 0;

    return (
      <div className="flex flex-col h-full">
        {cart.length === 0 ? (
          <div className="flex-1 flex flex-col items-center justify-center py-16 px-4">
            <div className="w-24 h-24 bg-gradient-to-br from-[#1e4d3d]/10 to-[#7fb685]/10 rounded-full flex items-center justify-center mb-4">
              <ShoppingCart className="w-12 h-12 text-[#1e4d3d]/40" />
            </div>
            <h3 className="text-lg text-foreground mb-2">Votre panier est vide</h3>
            <p className="text-sm text-muted-foreground text-center mb-6">
              Ajoutez des produits frais et locaux pour commencer vos achats
            </p>
            <Button 
              onClick={() => setIsCartOpen(false)}
              className="bg-[#ff6b35] hover:bg-[#ff5722] text-white"
            >
              Découvrir nos produits
            </Button>
          </div>
        ) : (
          <>
            {/* Header with item count and clear button */}
            <div className="flex items-center justify-between mb-4 pb-3 border-b border-border">
              <div className="flex items-center gap-2">
                <Badge variant="secondary" className="bg-[#1e4d3d]/10 text-[#1e4d3d] dark:bg-[#1e4d3d]/20 dark:text-[#7fb685]">
                  {getTotalItems()} article{getTotalItems() > 1 ? 's' : ''}
                </Badge>
              </div>
              <Button 
                variant="ghost" 
                size="sm"
                onClick={clearCart}
                className="text-red-600 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-950 h-8 px-2"
              >
                <Trash2 className="w-4 h-4 mr-1" />
                Vider
              </Button>
            </div>

            {/* Cart Items */}
            <div className="flex-1 overflow-y-auto pr-1 space-y-2.5 mb-3">
              {cart.map(item => (
                <Card key={item.id} className="overflow-hidden border-border/50 hover:border-[#1e4d3d]/30 dark:hover:border-[#7fb685]/30 transition-all">
                  <CardContent className="p-3">
                    <div className="flex gap-3">
                      {/* Product Image */}
                      <div className="relative flex-shrink-0">
                        <ImageWithFallback
                          src={item.image}
                          alt={item.name}
                          className="w-16 h-16 rounded-lg object-cover"
                        />
                        {item.inStock && (
                          <Badge className="absolute -top-1 -left-1 h-4 px-1 text-xs bg-green-500 hover:bg-green-500 text-white">
                            ✓
                          </Badge>
                        )}
                      </div>

                      {/* Product Info */}
                      <div className="flex-1 min-w-0 flex flex-col justify-between">
                        <div>
                          <div className="flex items-start justify-between gap-2 mb-0.5">
                            <h4 className="text-sm font-medium text-foreground dark:text-white line-clamp-1">
                              {item.name}
                            </h4>
                            <button
                              onClick={() => removeFromCart(item.id)}
                              className="text-muted-foreground hover:text-red-600 dark:hover:text-red-500 transition-colors p-1 -mt-1 flex-shrink-0"
                              aria-label="Retirer du panier"
                            >
                              <X className="w-4 h-4" />
                            </button>
                          </div>
                          <p className="text-xs text-muted-foreground dark:text-gray-400 mb-1">
                            {item.producer}
                          </p>
                          <div className="flex items-center gap-1.5">
                            <span className="text-sm font-medium text-[#ff6b35] dark:text-[#ff8c6b]">
                              {item.price.toFixed(2)} €
                            </span>
                            <span className="text-xs text-muted-foreground dark:text-gray-400">
                              / {item.unit}
                            </span>
                          </div>
                        </div>

                        {/* Quantity Controls */}
                        <div className="flex items-center justify-between mt-2">
                          <div className="flex items-center gap-1.5 bg-muted/50 dark:bg-muted/30 rounded-lg p-1">
                            <button
                              onClick={() => updateQuantity(item.id, -1)}
                              className="w-6 h-6 rounded-md bg-background hover:bg-[#1e4d3d] hover:text-white dark:hover:bg-[#7fb685] flex items-center justify-center transition-all active:scale-95"
                            >
                              <Minus className="w-3 h-3" />
                            </button>
                            <span className="text-sm font-medium w-7 text-center text-foreground dark:text-white">
                              {item.quantity}
                            </span>
                            <button
                              onClick={() => updateQuantity(item.id, 1)}
                              className="w-6 h-6 rounded-md bg-background hover:bg-[#1e4d3d] hover:text-white dark:hover:bg-[#7fb685] flex items-center justify-center transition-all active:scale-95"
                            >
                              <Plus className="w-3 h-3" />
                            </button>
                          </div>
                          
                          <div className="text-right">
                            <div className="text-sm font-semibold text-foreground dark:text-white">
                              {(item.price * item.quantity).toFixed(2)} €
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </CardContent>
                </Card>
              ))}
            </div>

            {/* Promo Code Section */}
            <div className="mb-3 p-2.5 bg-gradient-to-r from-[#7fb685]/10 to-[#1e4d3d]/10 dark:from-[#7fb685]/20 dark:to-[#1e4d3d]/20 rounded-lg border border-[#7fb685]/20 dark:border-[#7fb685]/30">
              <div className="flex items-center gap-2">
                <Tag className="w-4 h-4 text-[#1e4d3d] dark:text-[#7fb685] flex-shrink-0" />
                <Input 
                  placeholder="Code promo" 
                  className="flex-1 h-8 bg-background border-border/50 text-sm"
                />
                <Button size="sm" className="bg-[#1e4d3d] hover:bg-[#153a2d] dark:bg-[#7fb685] dark:hover:bg-[#6fa375] text-white h-8 px-3 text-xs">
                  Appliquer
                </Button>
              </div>
            </div>

            {/* Summary Section */}
            <div className="border-t border-border pt-3 space-y-3">
              {/* Savings Badge */}
              {savings > 0 && (
                <div className="flex items-center gap-2 p-2.5 bg-green-50 dark:bg-green-950/20 rounded-lg border border-green-200 dark:border-green-900">
                  <div className="w-7 h-7 bg-green-500 rounded-full flex items-center justify-center flex-shrink-0">
                    <Percent className="w-3.5 h-3.5 text-white" />
                  </div>
                  <div className="flex-1 min-w-0">
                    <p className="text-sm font-medium text-green-700 dark:text-green-400">
                      Livraison gratuite !
                    </p>
                    <p className="text-xs text-green-600 dark:text-green-500">
                      Vous économisez {savings.toFixed(2)} €
                    </p>
                  </div>
                </div>
              )}

              {/* Price Breakdown */}
              <div className="space-y-2">
                <div className="flex justify-between text-sm">
                  <span className="text-muted-foreground dark:text-gray-400">Sous-total</span>
                  <span className="font-medium text-foreground dark:text-white">{subtotal.toFixed(2)} €</span>
                </div>
                <div className="flex justify-between text-sm">
                  <span className="text-muted-foreground dark:text-gray-400">Livraison</span>
                  {shippingCost === 0 ? (
                    <span className="font-medium text-green-600 dark:text-green-500">Gratuite</span>
                  ) : (
                    <span className="font-medium text-foreground dark:text-white">{shippingCost.toFixed(2)} €</span>
                  )}
                </div>
                {subtotal < 30 && (
                  <div className="flex items-center gap-1.5 text-xs text-muted-foreground dark:text-gray-400 bg-muted/50 dark:bg-muted/30 p-2 rounded">
                    <span>Plus que {(30 - subtotal).toFixed(2)} € pour la livraison gratuite</span>
                  </div>
                )}
                <div className="border-t border-border pt-2 flex justify-between items-center">
                  <span className="font-semibold text-foreground dark:text-white">Total</span>
                  <div className="text-right">
                    <div className="text-xl font-bold text-[#1e4d3d] dark:text-[#7fb685]">
                      {total.toFixed(2)} €
                    </div>
                  </div>
                </div>
              </div>

              {/* Checkout Button */}
              <Button className="w-full bg-gradient-to-r from-[#1e4d3d] to-[#2a5f4a] hover:from-[#153a2d] hover:to-[#1e4d3d] dark:from-[#7fb685] dark:to-[#6fa375] dark:hover:from-[#6fa375] dark:hover:to-[#5f8f65] text-white h-11 font-medium shadow-lg shadow-[#1e4d3d]/20 dark:shadow-[#7fb685]/20 transition-all active:scale-[0.98]">
                Passer la commande
                <ArrowRight className="w-5 h-5 ml-2" />
              </Button>

              {/* Security Info */}
              <div className="flex items-center justify-center gap-2 text-xs text-muted-foreground dark:text-gray-400">
                <div className="w-4 h-4 bg-green-500/10 rounded-full flex items-center justify-center">
                  <div className="w-2 h-2 bg-green-500 rounded-full"></div>
                </div>
                <span>Paiement 100% sécurisé</span>
              </div>
            </div>
          </>
        )}
      </div>
    );
  };

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
                <SheetContent className="w-full sm:max-w-[420px] flex flex-col">
                  <SheetHeader className="mb-4">
                    <SheetTitle className="flex items-center gap-2 text-lg">
                      <ShoppingCart className="w-5 h-5" />
                      Mon Panier
                    </SheetTitle>
                  </SheetHeader>
                  <CartContent />
                </SheetContent>
              </Sheet>

              {/* User Menu - Desktop */}
              <DropdownMenu>
                <DropdownMenuTrigger asChild>
                  <button className="hidden lg:flex items-center gap-2 p-2 hover:bg-[#2a5f4a] rounded-lg transition-colors">
                    <Avatar className="w-8 h-8">
                      <AvatarFallback className="bg-[#ff6b35] text-white text-sm">
                        JD
                      </AvatarFallback>
                    </Avatar>
                    <span className="text-sm">Mon Compte</span>
                    <ChevronDown className="w-4 h-4" />
                  </button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" className="w-56">
                  <DropdownMenuLabel className="flex items-center gap-3 py-3">
                    <Avatar className="w-10 h-10">
                      <AvatarFallback className="bg-[#ff6b35] text-white">
                        JD
                      </AvatarFallback>
                    </Avatar>
                    <div className="flex flex-col">
                      <span className="text-sm">John Doe</span>
                      <span className="text-xs text-muted-foreground">john@example.com</span>
                    </div>
                  </DropdownMenuLabel>
                  <DropdownMenuSeparator />
                  <DropdownMenuItem className="cursor-pointer">
                    <UserCircle className="w-4 h-4 mr-2" />
                    Mon Profil
                  </DropdownMenuItem>
                  <DropdownMenuItem className="cursor-pointer">
                    <Package className="w-4 h-4 mr-2" />
                    Mes Commandes
                  </DropdownMenuItem>
                  <DropdownMenuItem className="cursor-pointer">
                    <Heart className="w-4 h-4 mr-2" />
                    Mes Favoris
                  </DropdownMenuItem>
                  <DropdownMenuItem className="cursor-pointer relative">
                    <Bell className="w-4 h-4 mr-2" />
                    Notifications
                    <Badge variant="destructive" className="ml-auto text-xs px-1.5 py-0">
                      3
                    </Badge>
                  </DropdownMenuItem>
                  <DropdownMenuSeparator />
                  <DropdownMenuItem className="cursor-pointer">
                    <Settings className="w-4 h-4 mr-2" />
                    Paramètres
                  </DropdownMenuItem>
                  <DropdownMenuItem 
                    className="cursor-pointer text-red-600 focus:text-red-600 focus:bg-red-50 dark:focus:bg-red-950"
                    onClick={onLogout}
                  >
                    <LogOut className="w-4 h-4 mr-2" />
                    Déconnexion
                  </DropdownMenuItem>
                </DropdownMenuContent>
              </DropdownMenu>
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
                  <h3 className="text-foreground dark:text-white mb-1 truncate">{product.name}</h3>
                  <p className="text-xs text-muted-foreground dark:text-gray-400 truncate">{product.producer}</p>
                </div>

                <div className="flex items-center gap-1 mb-3">
                  <Star className="w-4 h-4 fill-[#ff6b35] text-[#ff6b35]" />
                  <span className="text-sm text-foreground dark:text-gray-300">{product.rating}</span>
                </div>

                <div className="flex items-center justify-between">
                  <div>
                    <span className="text-[#1e4d3d] dark:text-[#7fb685]">{product.price.toFixed(2)} €</span>
                    <span className="text-sm text-muted-foreground dark:text-gray-400 ml-1">/ {product.unit}</span>
                  </div>
                  
                  <Button
                    onClick={() => addToCart(product)}
                    disabled={!product.inStock}
                    className="bg-[#ff6b35] hover:bg-[#e55a28] dark:bg-[#ff8c6b] dark:hover:bg-[#ff6b35] text-white shadow-md hover:shadow-lg transition-all"
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
      <div className="lg:hidden fixed bottom-0 left-0 right-0 bg-background dark:bg-card border-t border-border shadow-lg z-40">
        <div className="grid grid-cols-4 gap-1">
          <button className="flex flex-col items-center justify-center py-3 text-[#1e4d3d] dark:text-[#7fb685] bg-[#f0f9f4] dark:bg-[#1e4d3d]/20">
            <Home className="w-5 h-5 mb-1" />
            <span className="text-xs">Accueil</span>
          </button>
          
          <button className="flex flex-col items-center justify-center py-3 text-muted-foreground hover:text-[#1e4d3d] dark:hover:text-[#7fb685] hover:bg-accent transition-colors">
            <Grid className="w-5 h-5 mb-1" />
            <span className="text-xs">Catégories</span>
          </button>
          
          <Sheet open={isCartOpen} onOpenChange={setIsCartOpen}>
            <SheetTrigger asChild>
              <button className="flex flex-col items-center justify-center py-3 text-gray-600 hover:text-[#1e4d3d] dark:text-gray-400 dark:hover:text-[#7fb685] hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors relative">
                <ShoppingBag className="w-5 h-5 mb-1" />
                <span className="text-xs">Panier</span>
                {getTotalItems() > 0 && (
                  <div className="absolute top-1 right-1/4 bg-[#ff6b35] text-white text-xs w-5 h-5 rounded-full flex items-center justify-center">
                    {getTotalItems()}
                  </div>
                )}
              </button>
            </SheetTrigger>
            <SheetContent side="bottom" className="h-[85vh] flex flex-col">
              <SheetHeader className="mb-4">
                <SheetTitle className="flex items-center gap-2 text-lg">
                  <ShoppingCart className="w-5 h-5" />
                  Mon Panier
                </SheetTitle>
              </SheetHeader>
              <CartContent />
            </SheetContent>
          </Sheet>
          
          <button className="flex flex-col items-center justify-center py-3 text-muted-foreground hover:text-[#1e4d3d] dark:hover:text-[#7fb685] hover:bg-accent transition-colors">
            <User className="w-5 h-5 mb-1" />
            <span className="text-xs">Compte</span>
          </button>
        </div>
      </div>
    </div>
  );
}
