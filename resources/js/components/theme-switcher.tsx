import { useTheme } from '@/hooks/use-theme';
import { cn } from '@/lib/utils';
import { Check } from 'lucide-react';
import { type HTMLAttributes } from 'react';
import { Card, CardContent } from './ui/card';

export default function ThemeSwitcher({
    className = '',
    ...props
}: HTMLAttributes<HTMLDivElement>) {
    const { theme, updateTheme, themes } = useTheme();

    return (
        <div
            className={cn('grid gap-4 sm:grid-cols-2 lg:grid-cols-4', className)}
            {...props}
        >
            {themes.map((themeOption) => (
                <Card
                    key={themeOption.value}
                    className={cn(
                        'cursor-pointer transition-all hover:shadow-md',
                        theme === themeOption.value &&
                            'ring-2 ring-primary ring-offset-2'
                    )}
                    onClick={() => updateTheme(themeOption.value)}
                >
                    <CardContent className="p-4">
                        <div className="space-y-3">
                            {/* Theme Preview */}
                            <div className="flex h-16 items-center justify-center overflow-hidden rounded-md border bg-muted/30">
                                <div className="flex h-full w-full items-center justify-center gap-1 p-2">
                                    {/* Color dots preview */}
                                    <div
                                        className={cn(
                                            'h-8 w-8 rounded-full',
                                            themeOption.value === 'default' &&
                                                'bg-gradient-to-br from-slate-800 to-slate-900',
                                            themeOption.value === 'monochrome' &&
                                                'bg-gradient-to-br from-gray-400 to-gray-600',
                                            themeOption.value === 'ocean' &&
                                                'bg-gradient-to-br from-blue-500 to-teal-500',
                                            themeOption.value === 'forest' &&
                                                'bg-gradient-to-br from-green-600 to-emerald-600'
                                        )}
                                    />
                                    <div
                                        className={cn(
                                            'h-6 w-6 rounded-full',
                                            themeOption.value === 'default' &&
                                                'bg-gradient-to-br from-slate-600 to-slate-700',
                                            themeOption.value === 'monochrome' &&
                                                'bg-gradient-to-br from-gray-300 to-gray-500',
                                            themeOption.value === 'ocean' &&
                                                'bg-gradient-to-br from-cyan-400 to-blue-400',
                                            themeOption.value === 'forest' &&
                                                'bg-gradient-to-br from-lime-500 to-green-500'
                                        )}
                                    />
                                    <div
                                        className={cn(
                                            'h-4 w-4 rounded-full',
                                            themeOption.value === 'default' &&
                                                'bg-gradient-to-br from-slate-400 to-slate-500',
                                            themeOption.value === 'monochrome' &&
                                                'bg-gradient-to-br from-gray-200 to-gray-400',
                                            themeOption.value === 'ocean' &&
                                                'bg-gradient-to-br from-sky-300 to-cyan-300',
                                            themeOption.value === 'forest' &&
                                                'bg-gradient-to-br from-emerald-400 to-teal-400'
                                        )}
                                    />
                                </div>
                            </div>

                            {/* Theme Info */}
                            <div className="space-y-1">
                                <div className="flex items-center justify-between">
                                    <h3 className="font-semibold text-sm">
                                        {themeOption.label}
                                    </h3>
                                    {theme === themeOption.value && (
                                        <Check className="h-4 w-4 text-primary" />
                                    )}
                                </div>
                                <p className="text-xs text-muted-foreground">
                                    {themeOption.description}
                                </p>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            ))}
        </div>
    );
}

