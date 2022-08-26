import './bootstrap'
import '../css/app.css'

import { render } from 'react-dom'
import { createInertiaApp, ReactComponent } from '@inertiajs/inertia-react'
import { InertiaProgress } from '@inertiajs/progress'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel'

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent<ReactComponent>(`./Pages/${name}.tsx`, import.meta.glob<ReactComponent>('./Pages/**/*.tsx')),
  setup: ({ el, App, props }) => render(<App {...props} />, el),
})

InertiaProgress.init({
  delay: 0,
  color: 'orange',
  includeCSS: true,
  showSpinner: true,
})
