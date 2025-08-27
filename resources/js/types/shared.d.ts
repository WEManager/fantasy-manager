import type { Config } from 'ziggy-js'

export interface FlashProps {
  type: 'success' | 'error' | 'info' | 'warning'
  message: string
}

export interface SharedData extends Record<string, unknown> {
  name: string
  quote: { message: string; author: string }
  auth: unknown
  ziggy: Config & { location: string }
  sidebarOpen: boolean
  flash: FlashProps
}

export interface PageModule {
  default: {
    layout?: (page: React.ReactNode) => React.ReactNode
  }
}
