import type { OngoingGame } from '~/modules/ongoing-games/types'

import { usePage } from '@inertiajs/react'

import { OngoingGamesList } from '~/modules/ongoing-games/components/ongoing-games-list'

interface PageProps {
  ongoingGames?: OngoingGame[]
  [key: string]: any
}

export function OngoingGamesSection() {
  const { ongoingGames } = usePage<PageProps>().props

  return (
    <section className="mb-8">
      {ongoingGames && ongoingGames.length > 0 && <OngoingGamesList games={ongoingGames} />}
    </section>
  )
}
