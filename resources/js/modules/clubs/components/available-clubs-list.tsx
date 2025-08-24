import type { Club } from '../../core/types'

import { Link } from '@inertiajs/react'

import { useAuth } from '../../core/hooks/useAuth'

interface AvailableClubsListProps {
  clubs: Club[]
}

export function AvailableClubsList({ clubs }: AvailableClubsListProps) {
  const { isAuthenticated, hasClub } = useAuth()

  return (
    <div className="bg-white rounded-lg shadow-md p-6 mb-8">
      <h2 className="text-2xl font-bold mb-6 text-gray-800">Lista de Clubes Disponíveis</h2>

      <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
        {clubs.map((club) => (
          <ClubListItem key={club.id} club={club} canApply={isAuthenticated && !hasClub} />
        ))}
      </div>
    </div>
  )
}

interface ClubListItemProps {
  club: Club
  canApply: boolean
}

function ClubListItem({ club, canApply }: ClubListItemProps) {
  const hasManager = !!club.manager

  return (
    <div className="flex items-center gap-2 py-1">
      <span className={`flag-icon flag-icon-${club.locale.toLowerCase()}`} />

      <Link
        href={`/clubs/${club.slug}`}
        className="text-blue-600 hover:text-blue-800 hover:underline flex-1"
      >
        {club.name}
      </Link>

      <div
        className="w-3 h-3 rounded border"
        style={{
          backgroundColor: club.colors[0] || '#ccc',
          borderColor: club.colors[1] || '#999',
        }}
      />

      <span className="text-gray-500">-</span>

      {hasManager ? (
        <span className="text-sm text-gray-600">Manager: {club.manager?.name}</span>
      ) : canApply ? (
        <Link
          href={`/clubs/${club.slug}/apply`}
          className="text-sm text-green-600 hover:text-green-800 hover:underline"
        >
          Candidatar-se
        </Link>
      ) : (
        <span className="text-sm text-gray-500">Sem manager</span>
      )}
    </div>
  )
}
