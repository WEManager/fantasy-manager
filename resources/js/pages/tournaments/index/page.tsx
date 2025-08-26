import type { TournamentIndexData } from '~/modules/tournaments/types'

import { Head } from '@inertiajs/react'

import { TournamentsTable } from '~/modules/tournaments/components/tournaments-table'

export default function TournamentsIndexPage({ tournaments }: TournamentIndexData) {
  return (
    <>
      <Head title="Torneios" />

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div className="p-6 bg-white border-b border-gray-200">
              <div className="mb-6">
                <h3 className="text-lg font-medium text-gray-900 mb-2">Lista de Torneios</h3>
                <p className="text-sm text-gray-600">
                  Visualize todos os torneios disponíveis no sistema
                </p>
              </div>

              <TournamentsTable tournaments={tournaments} />
            </div>
          </div>
        </div>
      </div>
    </>
  )
}
