import type { GameShowData } from '~/modules/games/types'

import { Head } from '@inertiajs/react'

import { GameHeader } from '~/modules/games/components/game-header'
import { GameMessages } from '~/modules/games/components/game-messages'
import { LineupTable } from '~/modules/games/components/lineup-table'

export default function GameShowPage({ game }: GameShowData) {
  console.log('🚀 ~ GameShowPage ~ game:', game)
  // Verificação de segurança para os times
  if (!game.hometeam || !game.awayteam) {
    return (
      <div className="container mx-auto px-4 py-8">
        <div className="text-center">
          <h1 className="text-2xl font-bold text-red-600">Erro ao carregar dados do jogo</h1>
          <p className="text-gray-600 mt-2">Os times não foram encontrados.</p>
        </div>
      </div>
    )
  }

  return (
    <>
      <Head title={`${game.hometeam.name} vs ${game.awayteam.name}`} />

      <GameHeader game={game} />

      <div className="container mx-auto px-4 py-8">
        <div className="grid grid-cols-1 lg:grid-cols-5 gap-6">
          {/* Escalação Time da Casa */}
          <div className="lg:col-span-1">
            <LineupTable players={game.homeLineup} title={`Escalação - ${game.hometeam.name}`} />
          </div>

          {/* Eventos do Jogo */}
          <div className="lg:col-span-3">
            <GameMessages messages={game.messages} />
          </div>

          {/* Escalação Time Visitante */}
          <div className="lg:col-span-1">
            <LineupTable players={game.awayLineup} title={`Escalação - ${game.awayteam.name}`} />
          </div>
        </div>
      </div>
    </>
  )
}
