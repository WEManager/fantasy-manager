import { usePage } from '@inertiajs/inertia-react'
import { Page } from '@inertiajs/inertia'
import { Table } from 'flowbite-react'
import ClubShowGame from './ClubShowGame'

import type { ClubShowProps } from '../Show'

export default function ClubShowGames() {
  const { club } = usePage<Page<ClubShowProps>>().props

  return (
    <div>
      <Table>
        <Table.Head>
          <Table.HeadCell>Competição</Table.HeadCell>
          <Table.HeadCell>Data</Table.HeadCell>
          <Table.HeadCell></Table.HeadCell>
        </Table.Head>

        <Table.Body>
          {club.games
            ?.sort((gameA, gameB) => gameA.start_time.localeCompare(gameB.start_time))
            .map((game) => (
              <ClubShowGame key={game.id} game={game} />
            ))}
        </Table.Body>
      </Table>
    </div>
  )
}
