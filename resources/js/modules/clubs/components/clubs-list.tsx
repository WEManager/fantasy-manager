import type { Club } from '~/modules/core/types'

import { Link } from '@inertiajs/react'

import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'

interface ClubsListProps {
  clubs: Club[]
}

export function ClubsList({ clubs }: ClubsListProps) {
  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      {clubs.map((club) => (
        <Card key={club.id} className="hover:shadow-lg transition-shadow">
          <CardHeader>
            <CardTitle className="text-lg">
              <Link
                href={route('club.show', { club: club.slug })}
                className="text-blue-600 hover:text-blue-800 transition-colors"
              >
                {club.name}
              </Link>
            </CardTitle>
          </CardHeader>
          <CardContent>
            <div className="flex items-center justify-between">
              <div className="flex items-center space-x-2">
                <span className={`flag-icon flag-icon-${club.locale.toLowerCase()}`} />
                <span className="text-sm text-gray-600">{club.locale}</span>
              </div>
              <div className="flex space-x-1">
                {club.colors.map((color, index) => (
                  <div
                    key={index}
                    className="w-4 h-4 rounded-full border border-gray-300"
                    style={{ backgroundColor: color }}
                  />
                ))}
              </div>
            </div>
          </CardContent>
        </Card>
      ))}
    </div>
  )
}
