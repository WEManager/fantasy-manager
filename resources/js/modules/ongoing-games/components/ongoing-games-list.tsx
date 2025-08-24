import type { OngoingGame } from '../types'

import { Link } from '@inertiajs/react'

import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'

import { useOngoingGamesQuery } from '../hooks/useOngoingGamesQuery'
import { ClubName } from './club-name'
import { GameStatus } from './game-status'
import { TournamentName } from './tournament-name'

export function OngoingGamesList() {
  const { games, isLoading, error } = useOngoingGamesQuery()

  if (isLoading) {
    return (
      <div className="flex justify-center items-center py-8">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
      </div>
    )
  }

  if (error) {
    return (
      <div className="text-center py-8 text-destructive">
        <p>Erro ao carregar jogos: {error.message}</p>
      </div>
    )
  }

  if (!games || games.length === 0) {
    return (
      <div className="text-center py-8 text-muted-foreground">
        <p>Nenhum jogo em andamento no momento</p>
      </div>
    )
  }

  return (
    <Card className="overflow-hidden">
      <CardHeader>
        <CardTitle className="text-foreground">Jogos em Andamento</CardTitle>
      </CardHeader>

      <CardContent className="p-0">
        <div className="overflow-x-auto">
          <table className="w-full">
            <tbody>
              {games.map((game: OngoingGame) => (
                <tr
                  key={game.id}
                  className="border-b border-border hover:bg-muted/50 transition-colors duration-200"
                >
                  <td className="px-6 py-4">
                    <TournamentName group={game.group} />
                  </td>
                  <td className="px-6 py-4">
                    <Link
                      href={`/games/${game.id}`}
                      className="text-primary hover:text-primary/80 transition-colors duration-200"
                    >
                      <GameStatus status={game.status} gameStatus={game.gameStatus} />
                    </Link>
                  </td>
                  <td className="px-6 py-4">
                    <ClubName club={game.hometeam} />
                  </td>
                  <td className="px-6 py-4 text-center font-semibold">
                    <Link
                      href={`/games/${game.id}`}
                      className="text-foreground hover:text-primary transition-colors duration-200"
                    >
                      {game.hometeam_score} - {game.awayteam_score}
                    </Link>
                  </td>
                  <td className="px-6 py-4 text-right">
                    <ClubName club={game.awayteam} />
                  </td>
                </tr>
              ))}
            </tbody>
          </table>
        </div>
      </CardContent>
    </Card>
  )
}
