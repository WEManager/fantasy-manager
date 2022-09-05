import { Link, usePage } from '@inertiajs/inertia-react'
import { Card } from 'flowbite-react'
import route from 'ziggy-js'
import { Club } from '@/interfaces/Club'

export default function ClubShowPlayers() {
  const { club } = usePage().props as { club: Club }

  return (
    <div>
      <h3>Total de jogadores: {club.players.length}</h3>
      <div className="grid grid-cols-2 md:grid-cols-4 gap-3">
        {club?.players.map((player) => (
          <Link href={route('player.show', player)} key={player.id}>
            <Card>
              <p className="font-normal text-gray-700 dark:text-gray-400">{player.know_as}</p>
            </Card>
          </Link>
        ))}
      </div>
    </div>
  )
}
