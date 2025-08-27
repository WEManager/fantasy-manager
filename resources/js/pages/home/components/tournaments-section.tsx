import type { Tournament } from '~/modules/tournaments/types'

import { usePage } from '@inertiajs/react'

import { TournamentsList } from '~/modules/tournaments/components/tournaments-list'

interface PageProps {
  tournaments?: Tournament[]
  [key: string]: unknown
}

export function TournamentsSection() {
  const { tournaments } = usePage<PageProps>().props

  return (
    <section className="mb-8">
      <h2 className="text-2xl font-bold mb-4 text-foreground">Torneios</h2>
      {tournaments && tournaments.length > 0 && <TournamentsList tournaments={tournaments} />}
    </section>
  )
}
