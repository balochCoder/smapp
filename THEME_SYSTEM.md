# Theme System Documentation

## Overview

The application now includes a comprehensive theme system that allows users to switch between different color palettes while maintaining support for both light and dark modes.

## How It Works

### 1. **Dual-Layer Theme System**

The system has two independent layers:

- **Appearance Mode** (Light/Dark): Controls brightness/contrast
- **Color Theme**: Controls the color palette (Default, Monochrome, Ocean, Forest)

These work together: Each color theme has both a light and dark variant. For example:
- Ocean Light mode = Blue-teal colors on light backgrounds
- Ocean Dark mode = Blue-teal colors on dark backgrounds

### 2. **Available Themes**

#### Default Theme
- **Description**: Original colorful theme with neutral grays
- **Primary Color**: Dark gray/black
- **Font**: Instrument Sans (sans-serif)
- **Border Radius**: 0.625rem (rounded)
- **Use Case**: Professional, versatile, works for any content

#### Monochrome Theme
- **Description**: Clean grayscale theme with sharp edges
- **Primary Color**: Various shades of gray
- **Font**: Geist Mono (monospace)
- **Border Radius**: 0rem (sharp corners)
- **Use Case**: Minimal, distraction-free interface
- **Special**: Monospace font for technical/developer feel, completely flat design

#### Ocean Theme
- **Description**: Blue-teal palette inspired by water
- **Primary Color**: Blue (#0000FF hue range)
- **Font**: Instrument Sans (sans-serif)
- **Border Radius**: 0.625rem (rounded)
- **Use Case**: Calm, professional, tech-focused

#### Forest Theme
- **Description**: Green nature-inspired palette
- **Primary Color**: Green (#008000 hue range)
- **Font**: Instrument Sans (sans-serif)
- **Border Radius**: 0.625rem (rounded)
- **Use Case**: Natural, growth-oriented, environmental

## For Users

### Changing Themes

1. Navigate to **Settings → Appearance**
2. You'll see two sections:
   - **Light / Dark Mode**: Toggle between light and dark
   - **Color Theme**: Select your preferred color palette
3. Click any theme card to apply it instantly
4. Your preference is saved automatically

### Theme Combinations

You can combine any theme with any mode:
- Default Light + Default Dark
- Monochrome Light + Monochrome Dark
- Ocean Light + Ocean Dark
- Forest Light + Forest Dark

### Font Changes

Each theme can have its own font family:
- **Default, Ocean, Forest**: Use Instrument Sans (clean sans-serif)
- **Monochrome**: Uses Geist Mono (monospace) for a technical/developer aesthetic

The font changes automatically when you switch themes - no additional configuration needed!

## For Developers

### File Structure

```
resources/
├── css/
│   └── app.css              # Theme definitions (CSS variables)
├── js/
    ├── hooks/
    │   └── use-theme.tsx    # Theme management hook
    ├── components/
    │   └── theme-switcher.tsx  # Theme selection UI
    ├── pages/
    │   └── settings/
    │       └── appearance.tsx   # Appearance settings page
    └── app.tsx              # Theme initialization
```

### Adding a New Theme

1. **Define CSS Variables** in `resources/css/app.css`:

```css
/* Your New Theme */
.theme-yourtheme {
    --background: oklch(...);
    --foreground: oklch(...);
    --primary: oklch(...);
    /* ... all other color variables */
    --radius: 0.625rem;
    
    /* Fonts - customize as needed */
    --font-family-sans: 'Your Font', ui-sans-serif, system-ui, sans-serif;
    --font-family-serif: ui-serif, Georgia, serif;
    --font-family-mono: ui-monospace, monospace;
}

.theme-yourtheme.dark {
    /* Dark mode variant - colors only, fonts inherit from light mode */
    --background: oklch(...);
    /* ... other dark mode colors */
}
```

2. **Add Theme to Hook** in `resources/js/hooks/use-theme.tsx`:

```typescript
export type Theme = 'default' | 'monochrome' | 'ocean' | 'forest' | 'yourtheme';

// In useTheme():
themes: [
    // ... existing themes
    { 
        value: 'yourtheme' as Theme, 
        label: 'Your Theme', 
        description: 'Description here' 
    },
]
```

3. **Update Theme Switcher** in `resources/js/components/theme-switcher.tsx`:

Add preview colors for your theme in the theme preview section.

4. **Update Initialization** in `resources/js/hooks/use-theme.tsx`:

```typescript
root.classList.remove('theme-default', 'theme-monochrome', 'theme-ocean', 'theme-forest', 'theme-yourtheme');
```

### Using Theme Variables

All theme variables are automatically available as Tailwind utilities:

```tsx
// Background and text
<div className="bg-background text-foreground">

// Primary colors
<Button className="bg-primary text-primary-foreground">

// Semantic colors
<div className="text-success">Success message</div>
<div className="text-destructive">Error message</div>
<div className="text-info">Info message</div>
<div className="text-warning">Warning message</div>

// Muted colors
<p className="text-muted-foreground">Secondary text</p>
```

### Theme CSS Variables

Each theme defines these variables (both light and dark modes):

**Layout Colors:**
- `--background`, `--foreground`
- `--card`, `--card-foreground`
- `--popover`, `--popover-foreground`

**Semantic Colors:**
- `--primary`, `--primary-foreground`
- `--secondary`, `--secondary-foreground`
- `--accent`, `--accent-foreground`
- `--muted`, `--muted-foreground`

**Status Colors:**
- `--destructive`, `--destructive-foreground` (red)
- `--success`, `--success-foreground` (green)
- `--warning`, `--warning-foreground` (amber)
- `--info`, `--info-foreground` (blue)

**UI Elements:**
- `--border`, `--input`, `--ring`

**Charts:**
- `--chart-1` through `--chart-5`

**Sidebar:**
- `--sidebar`, `--sidebar-foreground`
- `--sidebar-primary`, `--sidebar-primary-foreground`
- `--sidebar-accent`, `--sidebar-accent-foreground`
- `--sidebar-border`, `--sidebar-ring`

**Design Tokens:**
- `--radius` (border radius, e.g., 0.625rem or 0rem for monochrome)

**Font Families:**
- `--font-family-sans` (maps to Tailwind's `font-sans`)
- `--font-family-serif` (maps to Tailwind's `font-serif`)
- `--font-family-mono` (maps to Tailwind's `font-mono`)

### Font Usage

The theme system automatically applies the correct font family to your entire application:

```tsx
// No special class needed - font-sans is applied by default
<body className="font-sans">  // This is automatic

// Or use font-serif or font-mono explicitly
<p className="font-serif">Serif text</p>
<code className="font-mono">Monospace code</code>
```

**Font Examples by Theme:**
- **Default**: Instrument Sans (modern sans-serif)
- **Monochrome**: Geist Mono (technical monospace)
- **Ocean**: Instrument Sans (modern sans-serif)
- **Forest**: Instrument Sans (modern sans-serif)

## Color System (OKLCH)

All colors use OKLCH color space for:
- Better perceptual uniformity
- Consistent lightness across hues
- Wider gamut support
- Easier manipulation

Format: `oklch(Lightness Chroma Hue)`
- Lightness: 0-1 (0 = black, 1 = white)
- Chroma: 0-0.4 (saturation)
- Hue: 0-360 (color angle)

Example:
```css
--primary: oklch(0.55 0.15 240);  /* Blue */
/* L=0.55 (medium), C=0.15 (moderate saturation), H=240 (blue) */
```

## Storage

Theme preferences are stored in:
- **localStorage** key: `app-theme`
- **Values**: `'default'`, `'monochrome'`, `'ocean'`, `'forest'`

The theme persists across sessions and is applied immediately on page load.

## Best Practices

1. **Always use theme variables** instead of hardcoded colors
2. **Test in all themes** before deploying (especially Monochrome which has no shadows)
3. **Use semantic colors** (`success`, `destructive`, etc.) for consistent meaning
4. **Avoid theme-specific logic** in components; let CSS variables handle it
5. **Consider accessibility** - maintain proper contrast ratios in all themes

## Troubleshooting

**Theme not applying:**
- Clear localStorage and reload
- Check browser console for errors
- Verify theme class is on `<html>` element

**Colors look wrong:**
- Verify OKLCH values are in valid ranges
- Check both light and dark mode definitions
- Ensure all variables are defined for the theme

**Building errors:**
- Run `npm run build` to check for TypeScript errors
- Verify all imports are correct
- Check Tailwind CSS configuration

## Testing

To test the theme system:

1. Visit Settings → Appearance
2. Try each theme in both light and dark modes (8 combinations total)
3. Navigate through different pages to ensure consistency
4. Check form inputs, buttons, cards, and interactive elements
5. Verify localStorage persistence by refreshing the page

## Future Enhancements

Potential improvements:
- User-generated custom themes
- Theme import/export
- Per-page theme overrides
- Animated theme transitions
- Theme preview before applying
- Backend storage for logged-in users

