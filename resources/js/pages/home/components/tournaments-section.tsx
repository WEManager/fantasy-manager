import { TournamentsList } from '~/modules/tournaments/components/tournaments-list'

export function TournamentsSection() {
  return (
    <section className="mb-8">
      <h2 className="text-2xl font-bold mb-4 text-foreground">Torneios</h2>
      <TournamentsList />
    </section>
  )
}
