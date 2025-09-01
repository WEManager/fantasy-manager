import type { TournamentGroup } from '../types'

import { Link } from '@inertiajs/react'

interface TournamentNameProps {
  group: TournamentGroup
}

export function TournamentName({ group }: TournamentNameProps) {
  return (
    <Link
      href={route('tournament.show', { tournament: group.tournament })}
      className="text-blue-600 hover:text-blue-800 hover:underline"
    >
      <div className="flex items-center gap-2">
        <span className="text-sm text-gray-500">🏆</span>
        <span>{group.tournament.name}</span>
      </div>
      {group.name !== group.tournament.name && (
        <div className="text-sm text-gray-500 mt-1">{group.name}</div>
      )}
    </Link>
  )
}
