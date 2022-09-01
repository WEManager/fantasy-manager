import React from 'react'
import { renderToString } from 'react-dom/server'
import { createInertiaApp, ReactComponent } from '@inertiajs/inertia-react'
import createServer from '@inertiajs/server'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

const appName = 'WEManager Fantazy Manager'

createServer((page) =>
  createInertiaApp({
    page,
    render: renderToString,
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
      resolvePageComponent<ReactComponent>(
        `../views/pages/${name}.jsx`,
        import.meta.glob<ReactComponent>('../views/pages/**/*.tsx')
      ),
    setup: ({ App, props }) => <App {...props} />,
  })
)
