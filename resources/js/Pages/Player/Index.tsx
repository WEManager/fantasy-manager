import { Head, Link } from '@inertiajs/inertia-react'
import { Button } from 'flowbite-react'
import route from 'ziggy-js'
import Layout from '@/Layouts/Layout'
import { Paginate } from '@/interfaces/Paginate'
import { Player } from '@/interfaces/Player'

type PlayerIndexProps = {
  response: Paginate<Player>
}

export default function PlayerIndex({ response }: PlayerIndexProps) {
  const { data: players } = response

  return (
    <Layout>
      <Head title="Clubes" />

      {players.map((player) => (
        <div key={player.id}>
          <Link href={route('player.show', player)}>{player.know_as}</Link>
        </div>
      ))}

      <div className="flex gap-2 mt-4">
        {response.links.map((link) => (
          <Link href={link.url} key={link.label}>
            <Button>{link.label}</Button>
          </Link>
        ))}
      </div>
    </Layout>
  )
}
