import type { Game } from '../types'

import { Link } from '@inertiajs/react'

import { Card, CardContent } from '~/modules/core/components/ui/card'

interface GameHeaderProps {
  game: Game
}

export function GameHeader({ game }: GameHeaderProps) {
  const isActive = game.status === '1'
  const progressPercentage = isActive ? (game.CurrentMinute / 90) * 100 : 0

  return (
    <div className="w-full py-6">
      <div className="container mx-auto px-4">
        {/* Status e Torneio */}
        <div className="text-center mb-6">
          <div className="text-lg font-semibold mb-2">{game.gameStatus}</div>
          <div className="text-sm text-gray-600">
            {game.group.tournament.name} - {game.group.name}
          </div>
        </div>

        {/* Placar */}
        <Card className="mb-6">
          <CardContent className="p-6">
            <div className="grid grid-cols-3 items-center gap-4">
              {/* Time da Casa */}
              <div className="text-center">
                <Link
                  href={route('club.show', { club: game.hometeam.slug })}
                  className="text-xl font-bold hover:text-blue-600 transition-colors"
                >
                  {game.hometeam.name}
                </Link>
              </div>

              {/* Placar */}
              <div className="text-center">
                <div className="text-3xl font-bold">
                  {game.hometeam_score} - {game.awayteam_score}
                </div>
              </div>

              {/* Time Visitante */}
              <div className="text-center">
                <Link
                  href={route('club.show', { club: game.awayteam.slug })}
                  className="text-xl font-bold hover:text-blue-600 transition-colors"
                >
                  {game.awayteam.name}
                </Link>
              </div>
            </div>
          </CardContent>
        </Card>

        {/* Barra de Progresso (se jogo ativo) */}
        {isActive && (
          <div className="mb-6">
            <div className="w-full bg-gray-200 rounded-full h-2">
              <div
                className="bg-blue-600 h-2 rounded-full transition-all duration-300"
                style={{ width: `${progressPercentage}%` }}
              />
            </div>
            <div className="text-center text-sm text-gray-600 mt-2">{game.CurrentMinute}'</div>
          </div>
        )}
      </div>
    </div>
  )
}
