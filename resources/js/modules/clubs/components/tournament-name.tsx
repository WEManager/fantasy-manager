import { Link } from '@inertiajs/react'

interface TournamentNameProps {
  group: {
    id: number
    name: string
    tournament: {
      id: number
      name: string
      slug: string
    }
  }
  className?: string
}

export function TournamentName({ group, className = '' }: TournamentNameProps) {
  return (
    <Link
      href={route('tournament.show', { tournament: group.tournament.slug })}
      className={`text-secondary-foreground hover:text-secondary-foreground/80 underline transition-colors ${className}`}
    >
      {group.tournament.name} - {group.name}
    </Link>
  )
}
