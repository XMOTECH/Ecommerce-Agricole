import { useState } from 'react';
import { 
  Home, 
  ShoppingBag, 
  Users, 
  UserCircle, 
  BarChart3, 
  Megaphone, 
  Settings,
  Search,
  Bell,
  TrendingUp,
  ShoppingCart,
  Truck,
  AlertTriangle,
  Sprout,
  Menu,
  X,
  ChevronDown,
  LogOut,
  User,
  Shield
} from 'lucide-react';
import { Card, CardContent, CardHeader, CardTitle } from './ui/card';
import { Input } from './ui/input';
import { Badge } from './ui/badge';
import { Sheet, SheetContent, SheetHeader, SheetTitle } from './ui/sheet';
import { ScrollArea } from './ui/scroll-area';
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

interface AdminDashboardProps {
  onLogout: () => void;
}

export function AdminDashboard({ onLogout }: AdminDashboardProps) {
  const [isSidebarOpen, setIsSidebarOpen] = useState(false);
  const [activeMenu, setActiveMenu] = useState('Dashboard');

  const menuItems = [
    { icon: Home, label: 'Dashboard' },
    { icon: ShoppingBag, label: 'Produits' },
    { icon: Users, label: 'Producers' },
    { icon: UserCircle, label: 'Clients' },
    { icon: BarChart3, label: 'Analytics' },
    { icon: Megaphone, label: 'Marketing' },
    { icon: Settings, label: 'Settings' },
  ];

  const orders = [
    { id: 'AGR001', client: 'Jean Dupont', date: '15/10/2023', amount: '120 €', status: 'En attente', badge: 'orange' },
    { id: 'AGR002', client: 'Marie Martin', date: '15/10/2023', amount: '500 €', status: 'Traiter', badge: 'orange' },
    { id: 'AGR003', client: 'Pierre Durant', date: '14/10/2023', amount: '285 €', status: 'En attente', badge: 'orange' },
  ];

  const MobileMenu = () => (
    <Sheet open={isSidebarOpen} onOpenChange={setIsSidebarOpen}>
      <SheetContent side="left" className="w-[280px] p-0 bg-[#1e4d3d]">
        <SheetHeader className="p-6 border-b border-[#2a5f4a]">
          <SheetTitle className="flex items-center gap-3 text-white">
            <div className="w-12 h-12 bg-[#2a5f4a] rounded-lg flex items-center justify-center">
              <Sprout className="w-7 h-7" />
            </div>
            <span>AgriMarket</span>
          </SheetTitle>
        </SheetHeader>
        
        <ScrollArea className="h-[calc(100vh-100px)]">
          <nav className="py-4">
            {menuItems.map((item) => (
              <button
                key={item.label}
                onClick={() => {
                  setActiveMenu(item.label);
                  setIsSidebarOpen(false);
                }}
                className={`w-full px-6 py-3 flex items-center gap-3 transition-colors ${
                  activeMenu === item.label
                    ? 'bg-[#ff6b35] text-white' 
                    : 'text-gray-300 hover:bg-[#2a5f4a]'
                }`}
              >
                <item.icon className="w-5 h-5" />
                <span>{item.label}</span>
              </button>
            ))}
          </nav>
        </ScrollArea>
      </SheetContent>
    </Sheet>
  );

  return (
    <div className="flex min-h-screen bg-background">
      {/* Sidebar - Desktop */}
      <div className="hidden lg:flex w-[220px] xl:w-[260px] bg-[#1e4d3d] dark:bg-[#153a2d] text-white flex-col fixed h-screen">
        {/* Logo */}
        <div className="p-6 border-b border-[#2a5f4a]">
          <div className="flex items-center gap-3">
            <div className="w-12 h-12 bg-[#2a5f4a] rounded-lg flex items-center justify-center">
              <Sprout className="w-7 h-7" />
            </div>
            <span className="hidden xl:block">AgriMarket</span>
          </div>
        </div>

        {/* Menu Items */}
        <ScrollArea className="flex-1 py-4">
          <nav>
            {menuItems.map((item) => (
              <button
                key={item.label}
                onClick={() => setActiveMenu(item.label)}
                className={`w-full px-6 py-3 flex items-center gap-3 transition-colors ${
                  activeMenu === item.label
                    ? 'bg-[#ff6b35] text-white' 
                    : 'text-gray-300 hover:bg-[#2a5f4a]'
                }`}
              >
                <item.icon className="w-5 h-5" />
                <span>{item.label}</span>
              </button>
            ))}
          </nav>
        </ScrollArea>
      </div>

      {/* Mobile Menu */}
      <MobileMenu />

      {/* Main Content */}
      <div className="flex-1 lg:ml-[220px] xl:ml-[260px]">
        {/* Header */}
        <div className="bg-card border-b border-border px-4 lg:px-8 py-4 lg:py-6 sticky top-0 z-30 shadow-sm">
          <div className="flex items-center justify-between">
            <div className="flex items-center gap-4">
              <button
                onClick={() => setIsSidebarOpen(true)}
                className="lg:hidden p-2 hover:bg-accent rounded-lg transition-colors"
              >
                <Menu className="w-6 h-6 text-foreground" />
              </button>
              <h1 className="text-foreground">Tableau de Bord</h1>
            </div>
            
            <div className="flex items-center gap-2 lg:gap-4">
              <div className="hidden md:block relative">
                <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <Input 
                  placeholder="Rechercher..." 
                  className="pl-10 w-[200px] lg:w-[300px] h-10"
                />
              </div>
              
              {/* Theme Toggle */}
              <ThemeToggle />
              
              <button className="relative p-2 hover:bg-accent rounded-lg transition-colors">
                <Bell className="w-5 h-5 text-foreground" />
                <Badge variant="destructive" className="absolute -top-1 -right-1 w-5 h-5 p-0 flex items-center justify-center text-xs">
                  5
                </Badge>
              </button>
              
              {/* Admin Profile Menu */}
              <DropdownMenu>
                <DropdownMenuTrigger asChild>
                  <button className="flex items-center gap-2 ml-2 hover:bg-accent px-2 py-1.5 rounded-lg transition-colors">
                    <div className="hidden sm:block text-right">
                      <div className="text-sm text-foreground">Admin</div>
                      <div className="text-xs text-muted-foreground">Administrateur</div>
                    </div>
                    <Avatar className="w-10 h-10">
                      <AvatarFallback className="bg-[#ff6b35] text-white">
                        AD
                      </AvatarFallback>
                    </Avatar>
                    <ChevronDown className="w-4 h-4 text-muted-foreground hidden sm:block" />
                  </button>
                </DropdownMenuTrigger>
                <DropdownMenuContent align="end" className="w-64">
                  <DropdownMenuLabel className="flex items-center gap-3 py-3">
                    <Avatar className="w-12 h-12">
                      <AvatarFallback className="bg-[#ff6b35] text-white">
                        AD
                      </AvatarFallback>
                    </Avatar>
                    <div className="flex flex-col">
                      <span className="text-sm">Admin Principal</span>
                      <span className="text-xs text-muted-foreground">admin@agrimarket.com</span>
                      <Badge variant="secondary" className="w-fit mt-1 text-xs">
                        <Shield className="w-3 h-3 mr-1" />
                        Super Admin
                      </Badge>
                    </div>
                  </DropdownMenuLabel>
                  <DropdownMenuSeparator />
                  <DropdownMenuItem className="cursor-pointer">
                    <User className="w-4 h-4 mr-2" />
                    Mon Profil
                  </DropdownMenuItem>
                  <DropdownMenuItem className="cursor-pointer relative">
                    <Bell className="w-4 h-4 mr-2" />
                    Notifications
                    <Badge variant="destructive" className="ml-auto text-xs px-1.5 py-0">
                      5
                    </Badge>
                  </DropdownMenuItem>
                  <DropdownMenuItem className="cursor-pointer">
                    <Settings className="w-4 h-4 mr-2" />
                    Paramètres
                  </DropdownMenuItem>
                  <DropdownMenuSeparator />
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
        </div>

        {/* Dashboard Content */}
        <div className="p-4 lg:p-8 overflow-auto">
          {/* Stats Cards */}
          <div className="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
            <Card className="hover:shadow-lg transition-shadow">
              <CardContent className="p-4 lg:p-6">
                <div className="flex items-start justify-between mb-3">
                  <div className="text-sm text-gray-600">Revenu Total</div>
                  <div className="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center">
                    <TrendingUp className="w-5 h-5 text-green-600" />
                  </div>
                </div>
                <div className="text-gray-900 mb-2">14 850 €</div>
                <div className="text-xs text-green-600 flex items-center gap-1">
                  <TrendingUp className="w-3 h-3" />
                  +5.2% vs mois précédent
                </div>
              </CardContent>
            </Card>

            <Card className="hover:shadow-lg transition-shadow">
              <CardContent className="p-4 lg:p-6">
                <div className="flex items-start justify-between mb-3">
                  <div className="text-sm text-gray-600">Nouvelles Commandes</div>
                  <div className="w-10 h-10 bg-orange-50 rounded-lg flex items-center justify-center">
                    <ShoppingCart className="w-5 h-5 text-[#ff6b35]" />
                  </div>
                </div>
                <div className="text-gray-900 mb-2">32</div>
                <div className="flex items-center gap-2 mt-3">
                  <div className="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                    <div className="h-full w-[60%] bg-[#ff6b35] rounded-full"></div>
                  </div>
                  <span className="text-xs text-gray-600">5 en attente</span>
                </div>
              </CardContent>
            </Card>

            <Card className="hover:shadow-lg transition-shadow md:col-span-2 xl:col-span-1">
              <CardContent className="p-4 lg:p-6">
                <div className="flex items-start justify-between mb-3">
                  <div className="text-sm text-gray-600">Nouveaux Clients</div>
                  <div className="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                    <Truck className="w-5 h-5 text-blue-600" />
                  </div>
                </div>
                <div className="flex items-end gap-4 mb-2">
                  <span className="text-gray-900">68</span>
                  <span className="text-sm text-gray-500">/ 120 total</span>
                </div>
                <div className="text-xs text-gray-600">2 en attente de validation</div>
              </CardContent>
            </Card>
          </div>

          {/* Charts Row */}
          <div className="grid grid-cols-1 xl:grid-cols-3 gap-4 lg:gap-6 mb-6 lg:mb-8">
            <Card className="xl:col-span-2">
              <CardHeader>
                <CardTitle className="text-gray-700 flex items-center justify-between">
                  <span>Performance des Ventes</span>
                  <span className="text-sm text-gray-500">30 derniers jours</span>
                </CardTitle>
              </CardHeader>
              <CardContent>
                <div className="h-[200px] lg:h-[250px] relative">
                  <svg className="w-full h-full" viewBox="0 0 600 200" preserveAspectRatio="none">
                    {/* Grid lines */}
                    <line x1="0" y1="160" x2="600" y2="160" stroke="#e5e7eb" strokeWidth="1" />
                    <line x1="0" y1="120" x2="600" y2="120" stroke="#e5e7eb" strokeWidth="1" />
                    <line x1="0" y1="80" x2="600" y2="80" stroke="#e5e7eb" strokeWidth="1" />
                    <line x1="0" y1="40" x2="600" y2="40" stroke="#e5e7eb" strokeWidth="1" />
                    
                    {/* Revenue line (dark) */}
                    <polyline
                      points="50,140 150,120 250,90 350,100 450,60 550,40"
                      fill="none"
                      stroke="#1e4d3d"
                      strokeWidth="3"
                      strokeLinecap="round"
                    />
                    
                    {/* En préparation line (orange) */}
                    <polyline
                      points="50,160 150,155 250,140 350,130 450,115 550,100"
                      fill="none"
                      stroke="#ff6b35"
                      strokeWidth="3"
                      strokeLinecap="round"
                    />
                    
                    {/* Points */}
                    <circle cx="550" cy="40" r="5" fill="#1e4d3d" />
                    <circle cx="550" cy="100" r="5" fill="#ff6b35" />
                  </svg>
                  
                  {/* Legend */}
                  <div className="absolute top-0 right-0 flex flex-col gap-2 text-sm bg-white/80 backdrop-blur-sm p-2 rounded-lg">
                    <div className="flex items-center gap-2">
                      <div className="w-4 h-1 bg-[#1e4d3d] rounded-full"></div>
                      <span className="text-xs text-gray-600">Revenus</span>
                    </div>
                    <div className="flex items-center gap-2">
                      <div className="w-4 h-1 bg-[#ff6b35] rounded-full"></div>
                      <span className="text-xs text-gray-600">En préparation</span>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader>
                <CardTitle className="text-gray-700">Commandes en Cours</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="relative w-full aspect-square max-w-[200px] mx-auto">
                  <svg viewBox="0 0 200 200" className="w-full h-full -rotate-90">
                    {/* Background circle */}
                    <circle
                      cx="100"
                      cy="100"
                      r="70"
                      fill="none"
                      stroke="#e5e7eb"
                      strokeWidth="25"
                    />
                    {/* En cours (dark green) - 30% */}
                    <circle
                      cx="100"
                      cy="100"
                      r="70"
                      fill="none"
                      stroke="#1e4d3d"
                      strokeWidth="25"
                      strokeDasharray={`${2 * Math.PI * 70 * 0.3} ${2 * Math.PI * 70}`}
                      strokeDashoffset="0"
                    />
                    {/* En attente (orange) - 30% */}
                    <circle
                      cx="100"
                      cy="100"
                      r="70"
                      fill="none"
                      stroke="#ff6b35"
                      strokeWidth="25"
                      strokeDasharray={`${2 * Math.PI * 70 * 0.3} ${2 * Math.PI * 70}`}
                      strokeDashoffset={`-${2 * Math.PI * 70 * 0.3}`}
                    />
                    {/* Expédiées (light green) - 40% */}
                    <circle
                      cx="100"
                      cy="100"
                      r="70"
                      fill="none"
                      stroke="#7fb685"
                      strokeWidth="25"
                      strokeDasharray={`${2 * Math.PI * 70 * 0.4} ${2 * Math.PI * 70}`}
                      strokeDashoffset={`-${2 * Math.PI * 70 * 0.6}`}
                    />
                  </svg>
                  
                  {/* Center Text */}
                  <div className="absolute inset-0 flex items-center justify-center">
                    <div className="text-center">
                      <div className="text-gray-900">87</div>
                      <div className="text-xs text-gray-500">Total</div>
                    </div>
                  </div>
                </div>
                
                {/* Legend */}
                <div className="mt-4 space-y-2">
                  <div className="flex items-center justify-between text-sm">
                    <div className="flex items-center gap-2">
                      <div className="w-3 h-3 bg-[#1e4d3d] rounded"></div>
                      <span className="text-gray-600">En cours</span>
                    </div>
                    <span className="text-gray-900">30%</span>
                  </div>
                  <div className="flex items-center justify-between text-sm">
                    <div className="flex items-center gap-2">
                      <div className="w-3 h-3 bg-[#ff6b35] rounded"></div>
                      <span className="text-gray-600">En attente</span>
                    </div>
                    <span className="text-gray-900">30%</span>
                  </div>
                  <div className="flex items-center justify-between text-sm">
                    <div className="flex items-center gap-2">
                      <div className="w-3 h-3 bg-[#7fb685] rounded"></div>
                      <span className="text-gray-600">Expédiées</span>
                    </div>
                    <span className="text-gray-900">40%</span>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          {/* Bottom Row */}
          <div className="grid grid-cols-1 xl:grid-cols-3 gap-4 lg:gap-6">
            <Card className="xl:col-span-2">
              <CardHeader>
                <CardTitle className="text-gray-700">Commandes en Attente</CardTitle>
              </CardHeader>
              <CardContent>
                <div className="overflow-x-auto -mx-6 px-6">
                  <div className="inline-block min-w-full align-middle">
                    <div className="overflow-hidden">
                      {/* Desktop Table */}
                      <div className="hidden md:block">
                        <div className="grid grid-cols-6 gap-4 text-sm text-gray-600 pb-3 border-b mb-4">
                          <div>ID</div>
                          <div>Client</div>
                          <div>Date</div>
                          <div>Montant</div>
                          <div>Statut</div>
                          <div>Action</div>
                        </div>
                        {orders.map((order) => (
                          <div key={order.id} className="grid grid-cols-6 gap-4 text-sm items-center py-3 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <div className="text-gray-900">{order.id}</div>
                            <div className="text-gray-600">{order.client}</div>
                            <div className="text-gray-600">{order.date}</div>
                            <div className="text-gray-900">{order.amount}</div>
                            <div>
                              <Badge className="bg-[#ff6b35] hover:bg-[#e55a28]">
                                {order.status}
                              </Badge>
                            </div>
                            <div>
                              <button className="px-3 py-1 bg-[#1e4d3d] text-white rounded-lg text-xs hover:bg-[#153a2d] transition-colors">
                                Traiter
                              </button>
                            </div>
                          </div>
                        ))}
                      </div>
                      
                      {/* Mobile Cards */}
                      <div className="md:hidden space-y-3">
                        {orders.map((order) => (
                          <Card key={order.id}>
                            <CardContent className="p-4">
                              <div className="flex items-start justify-between mb-3">
                                <div>
                                  <div className="text-gray-900 mb-1">{order.id}</div>
                                  <div className="text-sm text-gray-600">{order.client}</div>
                                </div>
                                <Badge className="bg-[#ff6b35] hover:bg-[#e55a28]">
                                  {order.status}
                                </Badge>
                              </div>
                              <div className="flex items-center justify-between text-sm">
                                <div className="text-gray-600">{order.date}</div>
                                <div className="text-gray-900">{order.amount}</div>
                              </div>
                              <button className="w-full mt-3 px-3 py-2 bg-[#1e4d3d] text-white rounded-lg text-sm hover:bg-[#153a2d] transition-colors">
                                Traiter la commande
                              </button>
                            </CardContent>
                          </Card>
                        ))}
                      </div>
                    </div>
                  </div>
                </div>
              </CardContent>
            </Card>

            <Card>
              <CardHeader>
                <CardTitle className="text-gray-700 flex items-center gap-2">
                  <AlertTriangle className="w-5 h-5 text-[#ff6b35]" />
                  Stocks Faibles
                </CardTitle>
              </CardHeader>
              <CardContent className="space-y-4">
                <div className="p-3 bg-orange-50 rounded-lg border border-orange-100">
                  <div className="text-sm text-gray-900 mb-1">Tomates Cœur de Bœuf</div>
                  <div className="text-xs text-gray-600 flex items-center gap-2">
                    <div className="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                      <div className="h-full w-[20%] bg-[#ff6b35]"></div>
                    </div>
                    <span>5kg</span>
                  </div>
                </div>
                
                <div className="p-3 bg-orange-50 rounded-lg border border-orange-100">
                  <div className="text-sm text-gray-900 mb-1">Fromage de Chèvre</div>
                  <div className="text-xs text-gray-600 flex items-center gap-2">
                    <div className="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                      <div className="h-full w-[15%] bg-[#ff6b35]"></div>
                    </div>
                    <span>3 unités</span>
                  </div>
                </div>

                <button className="w-full mt-4 px-4 py-2 border border-[#1e4d3d] text-[#1e4d3d] rounded-lg text-sm hover:bg-[#1e4d3d] hover:text-white transition-colors">
                  Voir tous les stocks
                </button>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </div>
  );
}
