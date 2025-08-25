import type { Club } from '~/modules/core/types'

import { Link } from '@inertiajs/react'

interface ClubNameProps {
  club: Club
  className?: string
}

export function ClubName({ club, className = '' }: ClubNameProps) {
  return (
    <Link
      href={route('club.show', { club: club.slug })}
      className={`text-primary hover:text-primary/80 underline transition-colors ${className}`}
    >
      {club.name}
    </Link>
  )
}
