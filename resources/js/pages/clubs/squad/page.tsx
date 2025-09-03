import { Head, Link } from '@inertiajs/react'

import { Button } from '~/modules/core/components/ui/button'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '~/modules/core/components/ui/card'
import {
  Table,
  TableBody,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from '~/modules/core/components/ui/table'
import { age } from '~/modules/core/utils/date'

interface Player {
  id: number
  nation_fifa_id: number
  full_name: string
  know_as: string
  age: number
  form: number
  birth_date: string
  nation: {
    id: number
    name: string
  }
}

interface Club {
  id: number
  name: string
  colors: string[]
  manager?: {
    name: string
  }
  slug: string
}

interface SquadPageProps {
  club: Club
  players: Player[]
}

export default function SquadPage({ club, players }: SquadPageProps) {
  return (
    <div className="min-h-screen bg-background">
      <Head title={`Elenco de ${club.name}`} />

      {/* Club Header */}
      <div className="py-6 px-4" style={{ backgroundColor: club.colors?.[0] || '#1f2937' }}>
        <div className="container mx-auto">
          <div className="flex items-center justify-between">
            <div>
              <h1
                className="text-3xl font-bold mb-2"
                style={{
                  color: club.colors?.[1] || '#ffffff',
                  textShadow: `1px 1px 0 ${club.colors?.[2] || '#000000'},-1px 1px 0 ${club.colors?.[2] || '#000000'},1px -1px 0 ${club.colors?.[2] || '#000000'},-1px -1px 0 ${club.colors?.[2] || '#000000'},0px 1px 0 ${club.colors?.[2] || '#000000'},0px -1px 0 ${club.colors?.[2] || '#000000'},-1px 0px 0 ${club.colors?.[2] || '#000000'},1px 0px 0 ${club.colors?.[2] || '#000000'},2px 2px 0 ${club.colors?.[2] || '#000000'},-2px 2px 0 ${club.colors?.[2] || '#000000'},2px -2px 0 ${club.colors?.[2] || '#000000'},-2px -2px 0 ${club.colors?.[2] || '#000000'},0px 2px 0 ${club.colors?.[2] || '#000000'},0px -2px 0 ${club.colors?.[2] || '#000000'},-2px 0px 0 ${club.colors?.[2] || '#000000'},2px 0px 0 ${club.colors?.[2] || '#000000'},1px 2px 0 ${club.colors?.[2] || '#000000'},-1px 2px 0 ${club.colors?.[2] || '#000000'},1px -2px 0 ${club.colors?.[2] || '#000000'},-1px -2px 0 ${club.colors?.[2] || '#000000'},2px 1px 0 ${club.colors?.[2] || '#000000'},-2px 1px 0 ${club.colors?.[2] || '#000000'},2px -1px 0 ${club.colors?.[2] || '#000000'},-2px -1px 0 ${club.colors?.[2] || '#000000'}`,
                }}
              >
                {club.name}
                <span className="text-sm text-gray-500">🏟️</span>
              </h1>

              {club.manager ? (
                <p className="text-sm" style={{ color: club.colors?.[1] || '#ffffff' }}>
                  Gerente: {club.manager.name}
                </p>
              ) : (
                <div className="flex items-center gap-2">
                  <p className="text-sm" style={{ color: club.colors?.[1] || '#ffffff' }}>
                    Sem gerente.
                  </p>
                  <Link href={route('club.apply', { club: club.slug })}>
                    <Button variant="outline" size="sm" className="text-xs">
                      Candidatar-se
                    </Button>
                  </Link>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Content */}
      <div className="container mx-auto p-4">
        <div className="grid grid-cols-1 lg:grid-cols-4 gap-6">
          {/* Players Table */}
          <div className="lg:col-span-3">
            <Card>
              <CardHeader>
                <CardTitle>Elenco</CardTitle>
                <CardDescription>Todos os jogadores do clube</CardDescription>
              </CardHeader>
              <CardContent>
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>Conhecido como</TableHead>
                      <TableHead>Nome completo</TableHead>
                      <TableHead>Idade</TableHead>
                      <TableHead>Nacionalidade</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {players.map((player) => (
                      <TableRow key={player.id}>
                        <TableCell>
                          <Link
                            href={route('player.show', { player: player.id })}
                            className="text-primary hover:underline font-medium"
                          >
                            {player.know_as}
                          </Link>
                        </TableCell>
                        <TableCell>
                          <Link
                            href={route('player.show', { player: player.id })}
                            className="text-primary hover:underline"
                          >
                            {player.full_name}
                          </Link>
                        </TableCell>
                        <TableCell>{age(player.birth_date)}</TableCell>
                        <TableCell>
                          <span
                            className={`flag-icon flag-icon-${player.nation.name.toLowerCase()}`}
                          >
                            {player.nation.name}
                          </span>
                        </TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </div>
  )
}
