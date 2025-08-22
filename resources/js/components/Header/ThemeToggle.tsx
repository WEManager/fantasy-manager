import { Theme, useSetTheme, useTheme } from '~/modules/core/contexts/theme-context'

import { Button } from '../../modules/core/components/ui/button'
import { Icons } from '../Icons'

export function ThemeToggle() {
  const theme = useTheme()
  const setTheme = useSetTheme()

  const isDark = theme === Theme.Dark

  const toggleTheme = () => {
    setTheme(theme === Theme.Light ? Theme.Dark : Theme.Light)
  }

  return (
    <Button variant="ghost" size="sm" onClick={toggleTheme}>
      {isDark ? (
        <Icons.sun className="h-4 w-4 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100" />
      ) : (
        <Icons.moon className="h-4 w-4 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-100" />
      )}
      <span className="sr-only">Toggle theme</span>
    </Button>
  )
}
