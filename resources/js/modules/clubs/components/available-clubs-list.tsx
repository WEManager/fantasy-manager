import type { Club } from '../../core/types'

import { Link } from '@inertiajs/react'

import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'

import { useAuth } from '../../core/hooks/useAuth'

interface AvailableClubsListProps {
  clubs: Club[]
}

export function AvailableClubsList({ clubs }: AvailableClubsListProps) {
  const { isAuthenticated, hasClub } = useAuth()

  return (
    <Card className="mb-8">
      <CardHeader>
        <CardTitle className="text-2xl font-bold text-foreground">
          Lista de Clubes Disponíveis
        </CardTitle>
      </CardHeader>

      <CardContent>
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {clubs.map((club) => (
            <ClubListItem key={club.id} club={club} canApply={isAuthenticated && !hasClub} />
          ))}
        </div>
      </CardContent>
    </Card>
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
      <span className="text-sm text-gray-500">🏟️</span>

      <Link
        href={route('club.show', { club: club.slug })}
        className="text-primary hover:text-primary/80 hover:underline flex-1 transition-colors duration-200"
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

      <span className="text-muted-foreground">-</span>

      {hasManager ? (
        <span className="text-sm text-muted-foreground">Manager: {club.manager?.name}</span>
      ) : canApply ? (
        <Link
          href={route('club.apply', { club: club.slug })}
          className="text-sm text-green-600 hover:text-green-700 hover:underline transition-colors duration-200"
        >
          Candidatar-se
        </Link>
      ) : (
        <span className="text-sm text-muted-foreground">Sem manager</span>
      )}
    </div>
  )
}
