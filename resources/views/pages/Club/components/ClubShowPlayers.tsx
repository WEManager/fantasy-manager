import { Link, usePage } from '@inertiajs/inertia-react'
import { Card } from 'flowbite-react'
import route from 'ziggy-js'
import { Page } from '@inertiajs/inertia'
import { ClubShowProps } from '../Show'

export default function ClubShowPlayers() {
  const { club } = usePage<Page<ClubShowProps>>().props

  if (!club.players) return null

  return (
    <div>
      <h3 className="mb-2 text-xl">Total de jogadores: {club.players.length}</h3>

      <div className="grid grid-cols-2 md:grid-cols-5 gap-3">
        {club?.players.map((player) => (
          <Link href={route('player.show', player)} key={player.id}>
            <Card>
              <div className="flex items-center gap-4">
                <img src={player.image_url} alt={player.know_as} className="w-10" />
                <span className="font-bold">{player.know_as}</span>
              </div>
            </Card>
          </Link>
        ))}
      </div>
    </div>
  )
}
