import React from 'react'
import { render } from 'react-dom'
import { createInertiaApp, ReactComponent } from '@inertiajs/inertia-react'
import { InertiaProgress } from '@inertiajs/progress'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

import '../css/app.css'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent<ReactComponent>(
      `../views/pages/${name}.tsx`,
      import.meta.glob<ReactComponent>('../views/pages/**/*.tsx')
    ),
  setup: ({ el, App, props }) => render(<App {...props} />, el),
})

InertiaProgress.init({
  delay: 0,
  color: 'orange',
  includeCSS: true,
  showSpinner: true,
})
