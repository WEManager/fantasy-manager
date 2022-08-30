import { PropsWithChildren } from 'react'
import { Nav } from '@/components'

export default function Layout({ children }: PropsWithChildren) {
  return (
    <>
      <Nav />

      <main>{children}</main>
    </>
  )
}
