import type { PageProps as InertiaPageProps } from '@inertiajs/core'

import { usePage } from '@inertiajs/react'

import { AvailableClubsList } from '~/modules/clubs/components/available-clubs-list'

interface PageProps extends InertiaPageProps {
  clubs?: Array<{
    id: number
    name: string
    slug: string
    locale: string
    colors: string[]
    manager?: {
      id: number
      name: string
    }
  }>
}

export function AvailableClubsSection() {
  const { clubs } = usePage<PageProps>().props

  return <AvailableClubsList clubs={clubs || []} />
}
