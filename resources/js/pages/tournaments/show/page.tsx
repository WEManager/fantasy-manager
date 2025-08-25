import type { Tournament } from '~/modules/tournaments/types'

import { Head } from '@inertiajs/react'

import { AppLayout } from '~/modules/layouts/app'
import { TournamentGroup } from '~/modules/tournaments/components/tournament-group'
import { TournamentHeader } from '~/modules/tournaments/components/tournament-header'

interface TournamentShowProps {
  tournament: Tournament
  participating_clubs?: any
  position_status?: string[]
  fixtures?: any[]
  selectedDate?: string
  loading?: boolean
}

export default function TournamentShowPage({
  tournament,
  position_status = [],
  fixtures = [],
  selectedDate,
  loading = false,
}: TournamentShowProps) {
  return (
    <AppLayout>
      <Head title={tournament.name} />

      <TournamentHeader tournament={tournament} />

      <div className="container mx-auto px-4 pb-8">
        <div className="space-y-12">
          {tournament.tournamentGroups?.map((group) => (
            <TournamentGroup
              key={group.id}
              id={group.id}
              name={group.name}
              standings={group.standings}
              positionStatus={position_status}
              fixtures={fixtures}
              selectedDate={selectedDate}
              loading={loading}
            />
          ))}
        </div>
      </div>
    </AppLayout>
  )
}
