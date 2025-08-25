import React from 'react'
import { Deferred } from '@inertiajs/react'

import { useAuth } from '~/modules/core/hooks/useAuth'

import { OngoingGamesSection } from './components/ongoing-games-section'
import { TournamentsSection } from './components/tournaments-section'

const AvailableClubsSection = React.lazy(() =>
  import('./components/available-clubs-section').then(({ AvailableClubsSection }) => ({
    default: AvailableClubsSection,
  })),
)

export default function Home() {
  const { isAuthenticated, hasClub } = useAuth()

  return (
    <div className="container mx-auto">
      <TournamentsSection />
      <OngoingGamesSection />

      {isAuthenticated && !hasClub && (
        <Deferred data="clubs" fallback={<div>Loading...</div>}>
          <AvailableClubsSection />
        </Deferred>
      )}
    </div>
  )
}
