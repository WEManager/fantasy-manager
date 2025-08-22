import { createInertiaApp } from '@inertiajs/react'
import createServer from '@inertiajs/react/server'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { renderToString } from 'react-dom/server'

// import route, { RouteParam, RouteParamsWithQueryOverload } from 'ziggy-js'
// import route from '../../vendor/tightenco/ziggy/dist/index.m'

const appName = 'WEManager Fantazy Manager'

createServer((page) =>
  createInertiaApp({
    page,
    render: renderToString,
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
      resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
    setup: ({ App, props }) => {
      // global.route = (name: string, params?: RouteParamsWithQueryOverload | RouteParam, absolute?: boolean) =>
      //   route(name, params, absolute, {
      //     ...(page.props.ziggy as any),
      //     location: new URL(page.props.ziggy.location),
      //   })

      return <App {...props} />
    },
  }),
)
