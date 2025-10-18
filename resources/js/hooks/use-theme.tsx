import { router } from '@inertiajs/react';
import { useEffect, useState } from 'react';

export type Theme = 'default' | 'monochrome' | 'ocean' | 'forest';

const THEME_KEY = 'app-theme';

export function initializeColorTheme() {
    if (typeof window === 'undefined') return;
    
    const savedTheme = (localStorage.getItem(THEME_KEY) as Theme) || 'default';
    const root = document.documentElement;
    
    // Remove all theme classes
    root.classList.remove('theme-default', 'theme-monochrome', 'theme-ocean', 'theme-forest');
    
    // Add saved theme class (or default if none saved)
    root.classList.add(`theme-${savedTheme}`);
}

export function useTheme() {
    const [theme, setTheme] = useState<Theme>(() => {
        if (typeof window === 'undefined') return 'default';
        return (localStorage.getItem(THEME_KEY) as Theme) || 'default';
    });

    useEffect(() => {
        const root = document.documentElement;
        
        // Remove all theme classes
        root.classList.remove('theme-default', 'theme-monochrome', 'theme-ocean', 'theme-forest');
        
        // Add current theme class
        root.classList.add(`theme-${theme}`);
        
        // Save to localStorage
        localStorage.setItem(THEME_KEY, theme);
    }, [theme]);

    const updateTheme = (newTheme: Theme) => {
        setTheme(newTheme);
        
        // Persist theme to backend if needed
        router.reload({ only: [] });
    };

    return {
        theme,
        updateTheme,
        themes: [
            { value: 'default' as Theme, label: 'Default', description: 'Instrument Sans with colors' },
            { value: 'monochrome' as Theme, label: 'Monochrome', description: 'Geist Mono & grayscale' },
            { value: 'ocean' as Theme, label: 'Ocean', description: 'Blue-teal water theme' },
            { value: 'forest' as Theme, label: 'Forest', description: 'Green nature theme' },
        ],
    };
}

