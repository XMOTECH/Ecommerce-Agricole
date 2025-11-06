import { Moon, Sun } from 'lucide-react';
import { useTheme } from './ThemeProvider';
import { Button } from './ui/button';

interface ThemeToggleProps {
  variant?: 'default' | 'outline' | 'ghost';
  showLabel?: boolean;
  className?: string;
}

export function ThemeToggle({ variant = 'ghost', showLabel = false, className = '' }: ThemeToggleProps) {
  const { theme, toggleTheme } = useTheme();

  return (
    <Button
      variant={variant}
      size={showLabel ? 'default' : 'icon'}
      onClick={toggleTheme}
      className={`transition-all ${className}`}
      title={theme === 'light' ? 'Passer au mode sombre' : 'Passer au mode clair'}
    >
      {theme === 'light' ? (
        <>
          <Moon className={showLabel ? 'w-5 h-5 mr-2' : 'w-5 h-5'} />
          {showLabel && <span className="text-sm">Mode sombre</span>}
        </>
      ) : (
        <>
          <Sun className={showLabel ? 'w-5 h-5 mr-2' : 'w-5 h-5'} />
          {showLabel && <span className="text-sm">Mode clair</span>}
        </>
      )}
    </Button>
  );
}
