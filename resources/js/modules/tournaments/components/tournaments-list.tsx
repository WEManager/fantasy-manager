import type { TournamentStatus } from '../types'

import { Link } from '@inertiajs/react'

import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'

import { TournamentStatusIcon } from './tournament-status-icon'

interface TournamentListItem {
  id: number
  slug: string
  name: string
  status: TournamentStatus
}

interface TournamentsListProps {
  tournaments: TournamentListItem[]
}

export function TournamentsList({ tournaments }: TournamentsListProps) {
  if (!tournaments || tournaments.length === 0) {
    return (
      <div className="text-center py-8 text-gray-500">
        <p>Nenhum torneio encontrado</p>
      </div>
    )
  }

  const tournamentsList = tournaments

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
      <Card className="transition-colors duration-200">
        <CardHeader>
          <CardTitle className="text-foreground">Torneios</CardTitle>
        </CardHeader>
        <CardContent>
          <ul className="space-y-2">
            {tournamentsList
              .filter((tournament: TournamentListItem) => tournament.status !== 'NOT_DECIDED')
              .map((tournament: TournamentListItem) => (
                <li key={tournament.id} className="flex items-center gap-2">
                  <TournamentStatusIcon status={tournament.status} />
                  <Link
                    href={route('tournament.show', { tournament: tournament.slug })}
                    className="text-primary hover:text-primary/80 hover:underline text-sm transition-colors duration-200"
                  >
                    {tournament.name}
                  </Link>
                </li>
              ))}
          </ul>
        </CardContent>
      </Card>
    </div>
  )
}
