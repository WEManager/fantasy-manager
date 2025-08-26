import type { PlayerShowData } from '~/modules/players/types'

import { Head } from '@inertiajs/react'

import { PlayerHeader } from '~/modules/players/components/player-header'
import { StatsCard } from '~/modules/players/components/stats-card'

export default function PlayerShowPage({ person }: PlayerShowData) {
  return (
    <>
      <Head title={`${person.know_as} jogando por ${person.club.name}`} />

      <PlayerHeader player={person} />

      <div className="container mx-auto px-4 py-8">
        <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
          {/* Estatísticas Técnicas */}
          <StatsCard title="Técnico" stats={person.technical} />

          {/* Estatísticas Mentais */}
          <StatsCard title="Mental" stats={person.mental} />

          {/* Estatísticas Físicas */}
          <StatsCard title="Físico" stats={person.physical} />

          {/* Estatísticas de Goleiro */}
          <StatsCard title="Goleiro" stats={person.goalkeeping} />
        </div>
      </div>
    </>
  )
}
