import type { TournamentStatus } from '../types'

import { Link } from '@inertiajs/react'

import { useTournamentQuery } from '../hooks/useTournamentQuery'
import { FlagIcon } from './FlagIcon'
import { TournamentStatusIcon } from './TournamentStatusIcon'

interface Tournament {
  id: number
  slug: string
  name: string
  nationality: string
  status: TournamentStatus
}

export function TournamentsList() {
  const { tournaments, isLoading, error } = useTournamentQuery()

  if (isLoading) {
    return (
      <div className="flex justify-center items-center py-8">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      </div>
    )
  }

  if (error) {
    return (
      <div className="text-center py-8 text-red-600">
        <p>Erro ao carregar torneios: {error.message}</p>
      </div>
    )
  }

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
        <div key={nationality} className="bg-white rounded-lg shadow-md p-4">
          <div className="flex items-center gap-2 mb-3">
            <FlagIcon nationality={nationality.toLowerCase()} />
            <h3 className="font-semibold text-gray-800">{nationality}</h3>
          </div>

          <ul className="space-y-2">
            {(leagues as Tournament[])
              .filter((league: Tournament) => league.status !== 'NOT_DECIDED')
              .map((league: Tournament) => (
                <li key={league.id} className="flex items-center gap-2">
                  <TournamentStatusIcon status={league.status} />
                  <Link
                    href={`/tournaments/${league.slug}`}
                    className="text-blue-600 hover:text-blue-800 hover:underline text-sm"
                  >
                    {league.name}
                  </Link>
                </li>
              ))}
          </ul>
        </div>
      ))}
    </div>
  )
}
