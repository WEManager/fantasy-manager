import type { PropsWithChildren } from 'react'

import { ThemeProvider } from '../contexts/theme-context'

export function AppProviders({ children }: PropsWithChildren) {
  return <ThemeProvider>{children}</ThemeProvider>
}
