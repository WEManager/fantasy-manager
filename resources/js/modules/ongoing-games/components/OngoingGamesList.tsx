import { Link } from '@inertiajs/react'

import { useOngoingGamesQuery } from '../hooks/useOngoingGamesQuery'
import { ClubName } from './ClubName'
import { GameStatus } from './GameStatus'
import { TournamentName } from './TournamentName'

export function OngoingGamesList() {
  const { games, isLoading, error } = useOngoingGamesQuery()

  if (isLoading) {
    return (
      <div className="flex justify-center items-center py-8">
        <div className="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      </div>
    )
  }

  if (error) {
    return (
      <div className="text-center py-8 text-red-600">
        <p>Erro ao carregar jogos: {error.message}</p>
      </div>
    )
  }

  if (!games || games.length === 0) {
    return (
      <div className="text-center py-8 text-gray-500">
        <p>Nenhum jogo em andamento no momento</p>
      </div>
    )
  }

  return (
    <div className="bg-white rounded-lg shadow-md overflow-hidden">
      <div className="px-6 py-4 border-b border-gray-200">
        <h3 className="text-lg font-semibold text-gray-800">Jogos em Andamento</h3>
      </div>

      <div className="overflow-x-auto">
        <table className="w-full">
          <tbody>
            {games.map((game) => (
              <tr key={game.id} className="border-b border-gray-100 hover:bg-gray-50">
                <td className="px-6 py-4">
                  <TournamentName group={game.group} />
                </td>
                <td className="px-6 py-4">
                  <Link href={`/games/${game.id}`} className="text-blue-600 hover:text-blue-800">
                    <GameStatus status={game.status} gameStatus={game.gameStatus} />
                  </Link>
                </td>
                <td className="px-6 py-4">
                  <ClubName club={game.hometeam} />
                </td>
                <td className="px-6 py-4 text-center font-semibold">
                  <Link href={`/games/${game.id}`} className="text-gray-800 hover:text-blue-600">
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
    </div>
  )
}
