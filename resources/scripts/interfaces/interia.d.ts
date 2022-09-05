import type { Page, PageProps, Errors, ErrorBag } from '@inertiajs/inertia'

declare global {
  interface InteriaPage extends Page<PageProps> {
    props: {
      errors: Errors & ErrorBag
      auth: {
        user: {
          name: string
        }
      }
      laravelVersion: string
      phpVersion: string
    }
  }
}

export {}
