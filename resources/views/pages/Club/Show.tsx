import { Head } from '@inertiajs/inertia-react'
import { Club } from '@/interfaces/Club'
import Layout from '@/Layouts/Layout'
import ClubShowPlayers from './components/ClubShowPlayers'
import ClubShowGames from './components/ClubShowGames'

export type ClubShowProps = {
  club: Club
}

export default function ClubShow({ club }: ClubShowProps) {
  return (
    <Layout>
      <Head title={club.name} />

      <section>
        <div className="container mx-auto">
          <div className="py-5">
            <h1 className="text-3xl font-bold">{club.name}</h1>
          </div>

          <div className="mt-3 flex flex-col gap-4">
            <ClubShowPlayers />

            <ClubShowGames />
          </div>
        </div>
      </section>
    </Layout>
  )
}
