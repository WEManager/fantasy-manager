import type { Tournament } from '../types'

import { formatDate } from '~/modules/core/utils/string'

interface TournamentHeaderProps {
  tournament: Tournament
}

export function TournamentHeader({ tournament }: TournamentHeaderProps) {
  return (
    <div className="container mx-auto py-6">
      <div className="space-y-4">
        <h1 className="text-3xl font-bold text-gray-900 dark:text-white">{tournament.name}</h1>

        {tournament.status && (
          <div className="flex items-center gap-2">
            <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
              {tournament.status}
            </span>
          </div>
        )}

        {tournament.start_date && tournament.end_date && (
          <h4 className="text-lg text-gray-600 dark:text-gray-300">
            Em andamento entre {formatDate(tournament.start_date)} e{' '}
            {formatDate(tournament.end_date)}
          </h4>
        )}

        {tournament.tournamentGroups && tournament.tournamentGroups.length > 1 && (
          <p className="text-sm text-gray-500 dark:text-gray-400">
            {tournament.name} possui {tournament.tournamentGroups.length} grupos
          </p>
        )}
      </div>
    </div>
  )
}
