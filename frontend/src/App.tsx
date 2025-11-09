import { useState } from 'react';
import { AdminDashboard } from './components/AdminDashboard';
import { ClientShop } from './components/ClientShop';
import { Login } from './components/Login';
import { SignUp } from './components/SignUp';
import { ThemeProvider } from './components/ThemeProvider';

type View = 'login' | 'signup' | 'admin' | 'client';

export default function App() {
  const [view, setView] = useState<View>('login');
  const [isAuthenticated, setIsAuthenticated] = useState(false);

  const handleLogin = (role: 'admin' | 'client') => {
    setIsAuthenticated(true);
    setView(role);
  };

  const handleSignUp = (role: 'admin' | 'client') => {
    setIsAuthenticated(true);
    setView(role);
  };

  const handleLogout = () => {
    setIsAuthenticated(false);
    setView('login');
  };

  // Authentication views
  if (!isAuthenticated) {
    if (view === 'signup') {
      return (
        <ThemeProvider>
          <SignUp
            onNavigateToLogin={() => setView('login')}
            onSignUp={handleSignUp}
          />
        </ThemeProvider>
      );
    }
    
    return (
      <ThemeProvider>
        <Login
          onNavigateToSignup={() => setView('signup')}
          onLogin={handleLogin}
        />
      </ThemeProvider>
    );
  }

  // Authenticated views
  return (
    <ThemeProvider>
      <div className="min-h-screen bg-background">
        {/* Dev Mode Toggle - Hidden in production */}
        <div className="fixed bottom-4 right-4 z-50 bg-card rounded-full shadow-lg border border-border">
          <div className="flex gap-1 p-2">
            <button
              onClick={() => setView('client')}
              className={`px-4 py-2 rounded-full text-xs transition-all ${
                view === 'client'
                  ? 'bg-[#ff6b35] text-white shadow-md'
                  : 'text-muted-foreground hover:bg-accent'
              }`}
            >
              Client
            </button>
            <button
              onClick={() => setView('admin')}
              className={`px-4 py-2 rounded-full text-xs transition-all ${
                view === 'admin'
                  ? 'bg-[#1e4d3d] text-white shadow-md'
                  : 'text-muted-foreground hover:bg-accent'
              }`}
            >
              Admin
            </button>
          </div>
        </div>

        {view === 'admin' ? <AdminDashboard onLogout={handleLogout} /> : <ClientShop onLogout={handleLogout} />}
      </div>
    </ThemeProvider>
  );
}
