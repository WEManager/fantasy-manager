import type { PropsWithChildren } from 'react'

import { QueryClient, QueryClientProvider } from '@tanstack/react-query'

export function QueryProvider({ children }: PropsWithChildren) {
  const queryClient = new QueryClient()

  return <QueryClientProvider client={queryClient}>{children}</QueryClientProvider>
}
