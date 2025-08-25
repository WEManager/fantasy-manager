import { Link, router } from '@inertiajs/react'
import { ChevronLeft, ChevronRight } from 'lucide-react'

import { Button } from '~/modules/core/components/ui/button'
import { Loading } from '~/modules/core/components/ui/loading'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '~/modules/core/components/ui/table'

interface Fixture {
  id: number
  hometeam_id: number
  awayteam_id: number
  hometeam_score: number | null
  awayteam_score: number | null
  start_time: string
  status: number
  gameStatus: string
  hometeam: {
    id: number
    name: string
    slug: string
  }
  awayteam: {
    id: number
    name: string
    slug: string
  }
}

interface FixturesByDateProps {
  groupId: number
  fixtures?: Fixture[]
  selectedDate?: string
  loading?: boolean
}

export function FixturesByDate({
  groupId,
  fixtures = [],
  selectedDate = new Date().toISOString().split('T')[0],
  loading = false,
}: FixturesByDateProps) {
  const goToPreviousDay = () => {
    const prevDate = new Date(selectedDate)
    prevDate.setDate(prevDate.getDate() - 1)
    const newDate = prevDate.toISOString().split('T')[0]

    router.get(
      window.location.pathname,
      {
        date: newDate,
        groupId: groupId,
      },
      {
        only: ['fixtures', 'selectedDate'],
        preserveState: true,
        preserveScroll: true,
      },
    )
  }

  const goToNextDay = () => {
    const nextDate = new Date(selectedDate)
    nextDate.setDate(nextDate.getDate() + 1)
    const newDate = nextDate.toISOString().split('T')[0]

    router.get(
      window.location.pathname,
      {
        date: newDate,
        groupId: groupId,
      },
      {
        only: ['fixtures', 'selectedDate'],
        preserveState: true,
        preserveScroll: true,
      },
    )
  }

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('pt-BR')
  }

  const getPreviousDay = () => {
    const prevDate = new Date(selectedDate)
    prevDate.setDate(prevDate.getDate() - 1)
    return formatDate(prevDate.toISOString())
  }

  const getNextDay = () => {
    const nextDate = new Date(selectedDate)
    nextDate.setDate(nextDate.getDate() + 1)
    return formatDate(nextDate.toISOString())
  }

  return (
    <div className="space-y-4">
      <div className="flex items-center justify-between">
        <h2 className="text-xl font-semibold text-gray-900 dark:text-white">
          Jogos em {formatDate(selectedDate)}
        </h2>

        <div className="flex items-center gap-2">
          <Button variant="outline" size="sm" onClick={goToPreviousDay} disabled={loading}>
            <ChevronLeft className="h-4 w-4 mr-1" />
            {getPreviousDay()}
          </Button>

          <Button variant="outline" size="sm" onClick={goToNextDay} disabled={loading}>
            {getNextDay()}
            <ChevronRight className="h-4 w-4 ml-1" />
          </Button>
        </div>
      </div>

      {loading ? (
        <div className="text-center py-8">
          <Loading size="md" />
        </div>
      ) : fixtures.length > 0 ? (
        <div className="overflow-x-auto">
          <Table>
            <TableHeader>
              <TableRow>
                <TableHead>Status</TableHead>
                <TableHead>Time Casa</TableHead>
                <TableHead className="text-center">Placar</TableHead>
                <TableHead>Time Visitante</TableHead>
              </TableRow>
            </TableHeader>
            <TableBody>
              {fixtures.map((fixture) => (
                <TableRow key={fixture.id}>
                  <TableCell>
                    <Link
                      href={route('game.show', { game: fixture.id })}
                      className="text-blue-600 hover:text-blue-800 hover:underline"
                    >
                      {fixture.gameStatus}
                    </Link>
                  </TableCell>
                  <TableCell>
                    <Link
                      href={route('club.show', { club: fixture.hometeam.slug })}
                      className="text-gray-900 dark:text-white hover:text-blue-600 hover:underline"
                    >
                      {fixture.hometeam.name}
                    </Link>
                  </TableCell>
                  <TableCell className="text-center">
                    {fixture.status > 0 ? (
                      <span className="font-semibold">
                        {fixture.hometeam_score} - {fixture.awayteam_score}
                      </span>
                    ) : (
                      <span className="text-gray-400">-</span>
                    )}
                  </TableCell>
                  <TableCell>
                    <Link
                      href={route('club.show', { club: fixture.awayteam.slug })}
                      className="text-gray-900 dark:text-white hover:text-blue-600 hover:underline"
                    >
                      {fixture.awayteam.name}
                    </Link>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </div>
      ) : (
        <div className="text-center py-8 text-gray-500 dark:text-gray-400">
          <p>Nenhum jogo em {formatDate(selectedDate)}</p>
        </div>
      )}
    </div>
  )
}
