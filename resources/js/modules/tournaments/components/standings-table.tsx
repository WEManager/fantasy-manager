import { Link } from '@inertiajs/react'

import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '~/modules/core/components/ui/table'

interface Standing {
  id: number
  club_id: number
  group_id: number
  won: number
  tie: number
  lost: number
  scored: number
  conceded: number
  points: number
  club: {
    id: number
    name: string
    slug: string
  }
}

interface StandingsTableProps {
  standings: Standing[]
  positionStatus?: string[]
}

export function StandingsTable({ standings, positionStatus = [] }: StandingsTableProps) {
  const getPositionClass = (index: number) => {
    if (!positionStatus[index]) return ''

    switch (positionStatus[index]) {
      case 'champion':
        return 'bg-yellow-100 dark:bg-yellow-900'
      case 'qualified':
        return 'bg-green-100 dark:bg-green-900'
      case 'relegated':
        return 'bg-red-100 dark:bg-red-900'
      default:
        return ''
    }
  }

  return (
    <div className="overflow-x-auto">
      <Table>
        <TableHeader>
          <TableRow>
            <TableHead>Pos.</TableHead>
            <TableHead>Clube</TableHead>
            <TableHead className="text-center">Jogos</TableHead>
            <TableHead className="text-center">Vit.</TableHead>
            <TableHead className="text-center">Emp.</TableHead>
            <TableHead className="text-center">Der.</TableHead>
            <TableHead className="text-center">+/-</TableHead>
            <TableHead className="text-right">Pontos</TableHead>
          </TableRow>
        </TableHeader>
        <TableBody>
          {standings.map((standing, index) => (
            <TableRow
              key={standing.club_id}
              className={getPositionClass(index)}
              title={positionStatus[index] || ''}
            >
              <TableCell className="font-medium">{index + 1}</TableCell>
              <TableCell>
                <Link
                  href={route('club.show', { club: standing.club.slug })}
                  className="text-gray-900 dark:text-white hover:text-blue-600 hover:underline"
                >
                  {standing.club.name}
                </Link>
              </TableCell>
              <TableCell className="text-center">
                {standing.won + standing.tie + standing.lost}
              </TableCell>
              <TableCell className="text-center">{standing.won}</TableCell>
              <TableCell className="text-center">{standing.tie}</TableCell>
              <TableCell className="text-center">{standing.lost}</TableCell>
              <TableCell className="text-center">{standing.scored - standing.conceded}</TableCell>
              <TableCell className="text-right font-semibold">{standing.points}</TableCell>
            </TableRow>
          ))}
        </TableBody>
      </Table>
    </div>
  )
}
