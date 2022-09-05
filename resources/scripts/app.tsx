import { createRoot } from 'react-dom/client'
import { createInertiaApp, ReactComponent } from '@inertiajs/inertia-react'
import { InertiaProgress } from '@inertiajs/progress'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import moment from 'moment'
import 'moment/dist/locale/pt-br'

import '../css/app.css'

const appName = 'WEManager Fantazy Manager'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent<ReactComponent>(
      `../views/pages/${name}.tsx`,
      import.meta.glob<ReactComponent>('../views/pages/**/*.tsx')
    ),
  setup: ({ el, App, props }) => {
    moment.locale('pt-br')

    return createRoot(el).render(<App {...props} />)
  },
})

InertiaProgress.init({
  delay: 0,
  color: 'orange',
  includeCSS: true,
  showSpinner: true,
})
