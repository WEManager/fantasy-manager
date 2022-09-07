import { renderToString } from 'react-dom/server'
import { createInertiaApp, ReactComponent } from '@inertiajs/inertia-react'
import createServer from '@inertiajs/server'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import moment from 'moment'
import 'moment/dist/locale/pt-br'

const appName = 'WEManager Fantazy Manager'

createServer((page) =>
  createInertiaApp({
    page,
    render: renderToString,
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
      resolvePageComponent<ReactComponent>(
        `../views/pages/${name}.tsx`,
        import.meta.glob<ReactComponent>('../views/pages/**/*.tsx')
      ),
    setup: ({ App, props }) => {
      moment.locale('pt-br')

      return <App {...props} />
    },
  })
)
