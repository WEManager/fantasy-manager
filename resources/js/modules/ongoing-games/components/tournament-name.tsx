import type { TournamentGroup } from '../types'

import { Link } from '@inertiajs/react'

interface TournamentNameProps {
  group: TournamentGroup
}

export function TournamentName({ group }: TournamentNameProps) {
  const slugify = (str: string) => {
    return str
      .toLowerCase()
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/(^-|-$)/g, '')
  }

  return (
    <Link
      href={`/tournaments/${group.tournament.slug}#${slugify(group.name)}`}
      className="text-blue-600 hover:text-blue-800 hover:underline"
    >
      <div className="flex items-center gap-2">
        <span className={`flag-icon flag-icon-${group.tournament.nationality.toLowerCase()}`} />
        <span>{group.tournament.name}</span>
      </div>
      {group.name !== group.tournament.name && (
        <div className="text-sm text-gray-500 mt-1">{group.name}</div>
      )}
    </Link>
  )
}
