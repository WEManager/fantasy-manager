import { type PropsWithChildren, useId } from 'react'

import { Flash } from '~/modules/core/components/flash'

import { Navbar } from './components/navbar'

export function AppLayout({ children }: PropsWithChildren) {
  const appId = useId()

  return (
    <>
      <Flash />
      <div id={appId}>
        <Navbar />
        <main className="py-4">{children}</main>
      </div>
    </>
  )
}
