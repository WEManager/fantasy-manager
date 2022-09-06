import { Page } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/inertia-react'
import PlayerShowStatList from './PlayerShowStatList'
import { PlayerShowProps } from '../Show'

export default function PlayerShowStats() {
  const { player } = usePage<Page<PlayerShowProps>>().props

  return (
    <div className="grid grid-cols-3 gap-6">
      {player?.technical && <PlayerShowStatList title="Técnica" stats={player.technical} />}

      {player?.mental && <PlayerShowStatList title="Mental" stats={player.mental} />}

      {player?.physical && <PlayerShowStatList title="Fisico" stats={player.physical} />}

      {player?.goalkeeping && <PlayerShowStatList title="Goleiro" stats={player.goalkeeping} />}
    </div>
  )
}
