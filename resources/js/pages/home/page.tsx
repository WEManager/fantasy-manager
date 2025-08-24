import React from 'react'
import { usePage } from '@inertiajs/react'

import { useAuth } from '~/modules/core/hooks/useAuth'
import { AppLayout } from '~/modules/layouts/app'

import { OngoingGamesSection } from './components/ongoing-games-section'
import { TournamentsSection } from './components/tournaments-section'

const AvailableClubsList = React.lazy(() =>
  import('~/modules/clubs/components/available-clubs-list').then(({ AvailableClubsList }) => ({
    default: AvailableClubsList,
  })),
)

interface PageProps {
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

export default function Home() {
  const { clubs } = usePage<PageProps>().props
  const { isAuthenticated, hasClub } = useAuth()

  return (
    <AppLayout>
      <div className="container">
        <TournamentsSection />
        <OngoingGamesSection />

        {isAuthenticated && !hasClub && <AvailableClubsList clubs={clubs || []} />}
      </div>
    </AppLayout>
  )
}
