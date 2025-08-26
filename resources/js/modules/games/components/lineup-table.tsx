import type { Player } from '../types'

import { Link } from '@inertiajs/react'

import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '~/modules/core/components/ui/table'

interface LineupTableProps {
  players: Record<string, Player>
  title: string
}

export function LineupTable({ players, title }: LineupTableProps) {
  return (
    <Card>
      <CardHeader>
        <CardTitle className="text-lg">{title}</CardTitle>
      </CardHeader>
      <CardContent>
        <Table>
          <TableHeader>
            <TableRow>
              <TableHead>Posição</TableHead>
              <TableHead>Jogador</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            {Object.entries(players).map(([position, player]) => (
              <TableRow key={position}>
                <TableCell className="font-medium">{position}</TableCell>
                <TableCell>
                  <Link
                    href={route('player.show', { player: player.id })}
                    className="text-blue-600 hover:text-blue-800 transition-colors"
                  >
                    {player.know_as}
                  </Link>
                </TableCell>
              </TableRow>
            ))}
          </TableBody>
        </Table>
      </CardContent>
    </Card>
  )
}
