import type { LineupEditData } from '~/modules/lineups/types'

import { Head } from '@inertiajs/react'

import { LineupEditor } from '~/modules/lineups/components/lineup-editor'

export default function LineupEditPage({ club, lineup, players }: LineupEditData) {
  return (
    <>
      <Head title={`${club.name} - Escalação`} />

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="mb-6">
            <h1 className="text-3xl font-bold text-gray-900 mb-2">{club.name}</h1>
            <p className="text-lg text-gray-600">Editar Escalação</p>
          </div>

          <LineupEditor lineup={lineup} players={players} />
        </div>
      </div>
    </>
  )
}
