import type { TournamentStatus } from '../types'

import { Link } from '@inertiajs/react'

import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'

import { FlagIcon } from './flag-icon'
import { TournamentStatusIcon } from './tournament-status-icon'

interface Tournament {
  id: number
  slug: string
  name: string
  nationality: string
  status: TournamentStatus
}

interface TournamentsListProps {
  tournaments: Tournament[]
}

export function TournamentsList({ tournaments }: TournamentsListProps) {
  if (!tournaments || tournaments.length === 0) {
    return (
      <div className="text-center py-8 text-gray-500">
        <p>Nenhum torneio encontrado</p>
      </div>
    )
  }

  // Agrupar torneios por nacionalidade
  const tournamentsByNationality = tournaments.reduce(
    (acc: Record<string, Tournament[]>, tournament: Tournament) => {
      const nationality = tournament.nationality || 'unknown'
      if (!acc[nationality]) {
        acc[nationality] = []
      }
      acc[nationality].push(tournament)
      return acc
    },
    {},
  )

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
      {Object.entries(tournamentsByNationality).map(([nationality, leagues]) => (
        <Card key={nationality} className="transition-colors duration-200">
          <CardHeader>
            <CardTitle className="flex items-center gap-2">
              <FlagIcon nationality={nationality.toLowerCase()} />
              <span className="text-foreground">{nationality}</span>
            </CardTitle>
          </CardHeader>

          <CardContent>
            <ul className="space-y-2">
              {(leagues as Tournament[])
                .filter((league: Tournament) => league.status !== 'NOT_DECIDED')
                .map((league: Tournament) => (
                  <li key={league.id} className="flex items-center gap-2">
                    <TournamentStatusIcon status={league.status} />
                    <Link
                      href={route('tournament.show', { tournament: league.slug })}
                      className="text-primary hover:text-primary/80 hover:underline text-sm transition-colors duration-200"
                    >
                      {league.name}
                    </Link>
                  </li>
                ))}
            </ul>
          </CardContent>
        </Card>
      ))}
    </div>
  )
}
