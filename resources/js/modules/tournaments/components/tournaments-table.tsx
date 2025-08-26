import type { Tournament } from '../types'

import { Link } from '@inertiajs/react'

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '~/modules/core/components/ui/table'

interface TournamentsTableProps {
  tournaments: Tournament[]
}

export function TournamentsTable({ tournaments }: TournamentsTableProps) {
  return (
    <div className="rounded-md border">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>ID</TableHead>
            <TableHead>Nome</TableHead>
            <TableHead>Tipo</TableHead>
            <TableHead>Status</TableHead>
            <TableHead className="text-right">Ações</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          {tournaments.map((tournament) => (
            <TableRow key={tournament.id}>
              <TableCell className="font-medium">
                <Link
                  href={route('tournament.show', { tournament: tournament.slug })}
                  className="text-blue-600 hover:text-blue-800 transition-colors"
                >
                  {tournament.id}
                </Link>
              </TableCell>
              <TableCell>
                <Link
                  href={route('tournament.show', { tournament: tournament.slug })}
                  className="text-blue-600 hover:text-blue-800 transition-colors"
                >
                  {tournament.name}
                </Link>
              </TableCell>
              <TableCell>{tournament.type}</TableCell>
              <TableCell>
                <span
                  className={`px-2 py-1 rounded-full text-xs font-medium ${
                    tournament.status === 'ACTIVE'
                      ? 'bg-green-100 text-green-800'
                      : tournament.status === 'NOT_STARTED'
                        ? 'bg-yellow-100 text-yellow-800'
                        : tournament.status === 'ENDED'
                          ? 'bg-gray-100 text-gray-800'
                          : 'bg-blue-100 text-blue-800'
                  }`}
                >
                  {tournament.status}
                </span>
              </TableCell>
              <TableCell className="text-right">
                <Link
                  href={route('tournament.show', { tournament: tournament.slug })}
                  className="text-blue-600 hover:text-blue-800 transition-colors"
                >
                  Ver
                </Link>
              </TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </div>
  )
}
