import type { Club } from '../types'

import { Link } from '@inertiajs/react'

interface ClubNameProps {
  club: Club
}

export function ClubName({ club }: ClubNameProps) {
  return (
    <div className="flex items-center gap-2">
      <Link
        href={`/clubs/${club.slug}`}
        className="text-gray-800 hover:text-blue-600 hover:underline"
      >
        {club.name}
      </Link>
      <div
        className="w-4 h-4 rounded border-2"
        style={{
          backgroundColor: club.colors[0] || '#ccc',
          borderColor: club.colors[1] || '#999',
        }}
      />
    </div>
  )
}
