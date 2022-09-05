import { Link, usePage } from '@inertiajs/inertia-react'
import { Page } from '@inertiajs/inertia'
import { Table, Tooltip } from 'flowbite-react'
import moment from 'moment'
import route from 'ziggy-js'
import { Game } from '@/interfaces/Game'
import type { ClubShowProps } from '../Show'

type ClubShowGameProps = {
  game: Game
}

export default function ClubShowGame({ game }: ClubShowGameProps) {
  const { club } = usePage<Page<ClubShowProps>>().props

  const canShowScoreboard =
    Number(game.status) === 1 ||
    Number(game.status) === 2 ||
    Number(game.status) === 3 ||
    Number(game.status) === 4 ||
    Number(game.status) === 5

  return (
    <Table.Row>
      <Table.Cell>{game.group.name}</Table.Cell>

      <Table.Cell>
        <Tooltip content={moment(game.start_time).format('dddd, Do \\d\\e\\ MMMM YYYY, H:mm A')} trigger="hover">
          <Link href={route('game.show', game)}>{moment(game.start_time).fromNow()}</Link>
        </Tooltip>
      </Table.Cell>

      <Table.Cell>
        <div className="flex">
          <div className="flex-1 flex mr-auto">
            <Link
              href={route('club.show', game.home_team)}
              className={`${game.home_team.id === club.id && 'font-bold'}`}>
              {game.home_team.name}
            </Link>
          </div>

          <Tooltip content={moment(game.start_time).format('dddd, Do \\d\\e\\ MMMM YYYY, H:mm A')} trigger="hover">
            <Link href={route('game.show', game)}>
              <div className="flex-1 flex justify-center gap-1 text-center">
                {canShowScoreboard ? (
                  <>
                    <span>{game.hometeam_score}</span> <span>vs</span> <span>{game.hometeam_score}</span>
                  </>
                ) : (
                  <span>vs</span>
                )}
              </div>
            </Link>
          </Tooltip>

          <div className="flex-1 flex justify-end ml-auto">
            <Link
              href={route('club.show', game.away_team)}
              className={`${game.away_team.id === club.id && 'font-bold'}`}>
              {game.away_team.name}
            </Link>
          </div>
        </div>
      </Table.Cell>
    </Table.Row>
  )
}
