import { TournamentsList } from '~/modules/tournaments/components/TournamentsList'

export function TournamentsSection() {
  return (
    <section className="mb-8">
      <h2 className="text-2xl font-bold mb-4 text-gray-800">Torneios</h2>
      <TournamentsList />
    </section>
  )
}
