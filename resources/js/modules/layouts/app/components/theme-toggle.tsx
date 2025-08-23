import type * as React from 'react'

import { Check, Monitor, Moon, Sun } from 'lucide-react'

import { Button } from '~/modules/core/components/ui/button'
import {
  DropdownMenu,
  DropdownMenuContent,
  DropdownMenuItem,
  DropdownMenuTrigger,
} from '~/modules/core/components/ui/dropdown-menu'
import { Theme, useSetTheme, useTheme } from '~/modules/core/contexts/theme-context'

interface ThemeToggleProps extends Omit<React.ComponentProps<typeof Button>, 'onClick'> {
  /** Mostrar texto ao lado do ícone */
  showText?: boolean
  /** Callback executado quando o tema é alterado */
  onThemeChange?: (theme: Theme) => void
  /** Usar dropdown em vez de toggle simples */
  mode?: 'toggle' | 'dropdown'
}

/**
 * Componente para alternar entre temas claro e escuro
 *
 * @example
 * ```tsx
 * <ThemeToggle />
 * <ThemeToggle showText />
 * <ThemeToggle mode="dropdown" />
 * <ThemeToggle onThemeChange={(theme) => console.log('Theme changed:', theme)} />
 * ```
 */
export function ThemeToggle({
  showText = false,
  onThemeChange,
  mode = 'toggle',
  className,
  ...props
}: ThemeToggleProps) {
  const theme = useTheme()
  const setTheme = useSetTheme()

  const isDark = theme === Theme.Dark

  const handleThemeChange = (newTheme: Theme) => {
    setTheme(newTheme)
    onThemeChange?.(newTheme)
  }

  if (mode === 'dropdown') {
    return (
      <DropdownMenu>
        <DropdownMenuTrigger asChild>
          <Button
            variant="ghost"
            size="icon"
            className={className}
            data-slot="theme-toggle"
            {...props}
          >
            {isDark ? (
              <Moon className="h-4 w-4" aria-hidden="true" />
            ) : theme === Theme.System ? (
              <Monitor className="h-4 w-4" aria-hidden="true" />
            ) : (
              <Sun className="h-4 w-4" aria-hidden="true" />
            )}
            <span className="sr-only">Toggle theme</span>
          </Button>
        </DropdownMenuTrigger>
        <DropdownMenuContent align="end">
          <DropdownMenuItem onClick={() => handleThemeChange(Theme.Light)}>
            <Sun className="mr-2 h-4 w-4" />
            <span>Light</span>
            {theme === Theme.Light && <Check className="ml-auto h-4 w-4" />}
          </DropdownMenuItem>
          <DropdownMenuItem onClick={() => handleThemeChange(Theme.Dark)}>
            <Moon className="mr-2 h-4 w-4" />
            <span>Dark</span>
            {theme === Theme.Dark && <Check className="ml-auto h-4 w-4" />}
          </DropdownMenuItem>
          <DropdownMenuItem onClick={() => handleThemeChange(Theme.System)}>
            <Monitor className="mr-2 h-4 w-4" />
            <span>System</span>
            {theme === Theme.System && <Check className="ml-auto h-4 w-4" />}
          </DropdownMenuItem>
        </DropdownMenuContent>
      </DropdownMenu>
    )
  }

  return (
    <Button
      variant="ghost"
      size="icon"
      onClick={() => handleThemeChange(theme === Theme.Light ? Theme.Dark : Theme.Light)}
      className={className}
      data-slot="theme-toggle"
      {...props}
    >
      {isDark ? (
        <Sun className="h-4 w-4" aria-hidden="true" />
      ) : (
        <Moon className="h-4 w-4" aria-hidden="true" />
      )}
      {showText && <span className="ml-2 text-sm">{isDark ? 'Light' : 'Dark'} mode</span>}
      <span className="sr-only">Toggle {isDark ? 'light' : 'dark'} theme</span>
    </Button>
  )
}
