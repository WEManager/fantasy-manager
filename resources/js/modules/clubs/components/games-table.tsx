import type { TournamentGame } from '../types'

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

import { ClubName } from './club-name'
import { TournamentName } from './tournament-name'

interface GamesTableProps {
  games: TournamentGame[]
  title: string
  isHomeGames?: boolean
}

export function GamesTable({ games, title, isHomeGames = false }: GamesTableProps) {
  return (
    <div className="mb-8">
      <Card>
        <CardHeader>
          <CardTitle className="text-xl">{title}</CardTitle>
        </CardHeader>
        <CardContent>
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Competição</TableHead>
                <TableHead>Status</TableHead>
                <TableHead>Placar</TableHead>
                <TableHead>{isHomeGames ? 'Time Visitante' : 'Time Casa'}</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {games.slice(0, 3).map((game) => (
                <TableRow key={game.id}>
                  <TableCell>
                    <TournamentName group={game.group} />
                  </TableCell>
                  <TableCell>
                    <Link
                      href={route('game.show', { game: game.id })}
                      className="text-primary hover:text-primary/80 underline"
                    >
                      {game.gameStatus}
                    </Link>
                  </TableCell>
                  <TableCell>
                    <Link
                      href={route('game.show', { game: game.id })}
                      className="text-foreground hover:text-primary transition-colors"
                    >
                      {game.status > 0 ? `${game.hometeam_score} - ${game.awayteam_score}` : '-'}
                    </Link>
                  </TableCell>
                  <TableCell>
                    <ClubName club={isHomeGames ? game.awayteam : game.hometeam} />
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </CardContent>
      </Card>
    </div>
  )
}
