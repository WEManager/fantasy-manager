import type { Player } from '../types'

import { Link } from '@inertiajs/react'

import { Button } from '~/modules/core/components/ui/button'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '~/modules/core/components/ui/table'

interface PlayersTableProps {
  players: Player[]
}

export function PlayersTable({ players }: PlayersTableProps) {
  console.log(players)

  return (
    <div className="rounded-md border">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Nome</TableHead>
            <TableHead>Idade</TableHead>
            <TableHead>Clube</TableHead>
            <TableHead>Nacionalidade</TableHead>
            <TableHead className="text-right">Ações</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          {players.map((player) => (
            <TableRow key={player.id}>
              <TableCell className="font-medium">{player.know_as}</TableCell>
              <TableCell>{player.age} anos</TableCell>
              <TableCell>{player.club?.name ?? 'N/A'}</TableCell>
              <TableCell>
                <div className="flex items-center gap-2">
                  {/* <FlagIcon nationality={player.nation.fifa_id} /> */}
                  <span>{player.nation.name}</span>
                </div>
              </TableCell>
              <TableCell className="text-right">
                <div className="flex justify-end gap-2">
                  <Button asChild variant="outline" size="sm">
                    <Link href={route('player.show', { player: player.id })}>Ver</Link>
                  </Button>
                </div>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </div>
  )
}
