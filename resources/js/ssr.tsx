import type { PageModule } from './types'

import { createInertiaApp } from '@inertiajs/react'
import createServer from '@inertiajs/react/server'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { renderToString } from 'react-dom/server'
import { route } from 'ziggy-js'

import { Ziggy as ziggy } from '~/ziggy'

import { AppLayout } from './modules/layouts/app'

const appName = import.meta.env.VITE_APP_NAME || 'Laravel'

createServer((page) =>
  createInertiaApp({
    page,
    render: renderToString,
    title: (title) => (title ? `${title} | ${appName}` : appName),
    resolve: (name) =>
      resolvePageComponent<PageModule>(
        `./pages/${name}.tsx`,
        import.meta.glob<PageModule>('./pages/**/*.tsx'),
      ).then((page) => {
        page.default.layout = page.default.layout || ((page) => <AppLayout>{page}</AppLayout>)

        return page
      }),
    setup: ({ App, props }) => {
      global.route = (name, params, absolute) =>
        route(name, params, absolute, {
          ...ziggy,
          location: new URL(page.props.ziggy.location),
        })

      return <App {...props} />
    },
  }),
)
