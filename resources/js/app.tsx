import { createInertiaApp } from '@inertiajs/react'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createRoot } from 'react-dom/client'

import { AppProviders } from './modules/core/providers/app-providers'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(`./pages/${name}.tsx`, import.meta.glob('./pages/**/*.tsx')),
  setup: ({ el, App, props }) => {
    if (!el) throw new Error('App element not found')

    const root = createRoot(el)
    root.render(
      <AppProviders>
        <App {...props} />
      </AppProviders>,
    )
  },
  progress: {
    delay: 0,
    color: 'orange',
    includeCSS: true,
    showSpinner: true,
  },
})
