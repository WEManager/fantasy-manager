import { PropsWithChildren } from 'react'
import { Nav } from '@/components'

export default function DefaultLayout({ children }: PropsWithChildren) {
  return (
    <>
      <Nav />

      <main className="container mx-auto mt-4">{children}</main>
    </>
  )
}
