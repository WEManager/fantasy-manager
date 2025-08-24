import type { PropsWithChildren } from 'react'

import { ThemeProvider } from '../contexts/theme-context'
import { QueryProvider } from './query-provider'

export function AppProviders({ children }: PropsWithChildren) {
  return (
    <ThemeProvider>
      <QueryProvider>{children}</QueryProvider>
    </ThemeProvider>
  )
}
