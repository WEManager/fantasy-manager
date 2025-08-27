import { createInertiaApp } from '@inertiajs/react'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createRoot, hydrateRoot } from 'react-dom/client'
import { useRoute } from 'ziggy-js'

import { Ziggy } from '~/ziggy'

import { AppProviders } from './modules/core/providers/app-providers'
import { AppLayout } from './modules/layouts/app'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createInertiaApp({
  title: (title) => (title ? `${title} - ${appName}` : appName),
  resolve: (name) =>
    resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')).then(
      (page: { default: { layout?: (page: any) => JSX.Element } }) => {
        page.default.layout = page.default.layout || ((page) => <AppLayout>{page}</AppLayout>)

        return page
      },
    ),
  setup: ({ el, App, props }) => {
    window.route = useRoute(Ziggy)

    const appElement = (
      <AppProviders>
        <App {...props} />
      </AppProviders>
    )

    if (import.meta.env.SSR) {
      hydrateRoot(el, appElement)
      return
    }

    createRoot(el).render(appElement)
  },
  progress: false,
})
