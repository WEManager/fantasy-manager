import type { PlayerShowData } from '~/modules/players/types'

import { Head } from '@inertiajs/react'

import { PlayerHeader } from '~/modules/players/components/player-header'
import { StatsCard } from '~/modules/players/components/stats-card'

export default function PlayerShowPage({ player }: PlayerShowData) {
  return (
    <>
      <Head title={`${player.know_as}${player.club && ` jogando por ${player.club.name}`}`} />

      <PlayerHeader player={player} />

      <div className="container mx-auto px-4 py-8">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {/* Estatísticas Técnicas */}
          <StatsCard title="Técnico" stats={player.stats?.technical} />

          {/* Estatísticas Mentais */}
          <StatsCard title="Mental" stats={player.stats?.mental} />

          {/* Estatísticas Físicas */}
          <StatsCard title="Físico" stats={player.stats?.physical} />

          {/* Estatísticas de Goleiro */}
          <StatsCard title="Goleiro" stats={player.stats?.goalkeeping} />
        </div>
      </div>
    </>
  )
}
