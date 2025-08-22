import type { PropsWithChildren } from 'react'

import { Header } from '~/components/Header'

type LayoutProps = PropsWithChildren & {
  header?: JSX.Element
}

export default function Layout({ children, header }: LayoutProps) {
  return (
    <>
      <Header />

      {header && (
        <header className="bg-white dark:bg-gray-700 shadow">
          <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">{header}</div>
        </header>
      )}

      <main>{children}</main>
    </>
  )
}
