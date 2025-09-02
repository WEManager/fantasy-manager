/** biome-ignore-all lint/correctness/useUniqueElementIds: <> */
import type { FixtureStatus as FixtureStatusType } from '../types'

interface GameStatusProps {
  status: FixtureStatusType
  gameStatus: string
}

export function GameStatus({ status, gameStatus }: GameStatusProps) {
  const getStatusIcon = () => {
    switch (status) {
      case '1':
        return (
          <svg
            className="w-4 h-4 text-orange-500"
            fill="currentColor"
            viewBox="0 0 20 20"
            aria-label="runningGameStatusIcon"
          >
            <title id="runningGameStatusIcon">Jogo em andamento status icon</title>
            <path
              fillRule="evenodd"
              d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
              clipRule="evenodd"
            />
          </svg>
        )
      case '2':
        return (
          <svg
            className="w-4 h-4 text-green-500"
            fill="currentColor"
            viewBox="0 0 20 20"
            aria-label="finishedGameStatusIcon"
          >
            <title id="finishedGameStatusIcon">Jogo finalizado status icon</title>
            <path
              fillRule="evenodd"
              d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
              clipRule="evenodd"
            />
          </svg>
        )
      default:
        return null
    }
  }

  return (
    <div className="flex items-center gap-2">
      {getStatusIcon()}
      <span>{gameStatus.replace(/<[^>]*>/g, '')}</span>
    </div>
  )
}
