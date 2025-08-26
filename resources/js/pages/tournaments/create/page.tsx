import type { TournamentCreateData } from '~/modules/tournaments/types'

import { Head } from '@inertiajs/react'

import { TournamentForm } from '~/modules/tournaments/components/tournament-form'

export default function TournamentCreatePage({ clubs }: TournamentCreateData) {
  return (
    <>
      <Head title="Criar Torneio" />

      <div className="py-12">
        <div className="max-w-2xl mx-auto sm:px-6 lg:px-8">
          <TournamentForm clubs={clubs} />
        </div>
      </div>
    </>
  )
}
