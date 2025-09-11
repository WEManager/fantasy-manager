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
  players: Record<string, Player> | null | undefined
  title: string
}

export function LineupTable({ players, title }: LineupTableProps) {
  console.log('🚀 ~ LineupTable ~ players:', players)
  console.log('🚀 ~ LineupTable ~ players type:', typeof players)
  console.log('🚀 ~ LineupTable ~ players is null/undefined:', players == null)

  // Verificação de segurança para players
  const safePlayers = players || {}
  const playerEntries = Object.entries(safePlayers)

  // Ordenar por slot para manter ordem da escalação
  const sortedPlayerEntries = playerEntries.sort(([, playerA], [, playerB]) => {
    return (playerA.slot || 0) - (playerB.slot || 0)
  })

  console.log('🚀 ~ LineupTable ~ sortedPlayerEntries:', sortedPlayerEntries)

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
            {sortedPlayerEntries.length > 0 ? (
              sortedPlayerEntries.map(([uniquePosition, player]) => (
                <TableRow key={uniquePosition}>
                  <TableCell className="font-medium">{player.position || uniquePosition}</TableCell>
                  <TableCell>
                    <Link
                      href={route('player.show', { player: player.id })}
                      className="text-blue-600 hover:text-blue-800 transition-colors"
                    >
                      {player.know_as}
                    </Link>
                  </TableCell>
                </TableRow>
              ))
            ) : (
              <TableRow>
                <TableCell colSpan={2} className="text-center text-gray-500">
                  Nenhuma escalação disponível
                </TableCell>
              </TableRow>
            )}
          </TableBody>
        </Table>
      </CardContent>
    </Card>
  )
}
