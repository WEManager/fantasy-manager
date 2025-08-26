import type { PlayerIndexData } from '~/modules/players/types'

import { Head } from '@inertiajs/react'

import { PlayersTable } from '~/modules/players/components/players-table'

export default function PlayersListPage({ players }: PlayerIndexData) {
  return (
    <>
      <Head title="Jogadores" />

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div className="p-6 bg-white border-b border-gray-200">
              <div className="mb-6">
                <h3 className="text-lg font-medium text-gray-900 mb-2">Lista de Jogadores</h3>
                <p className="text-sm text-gray-600">
                  Visualize todos os jogadores disponíveis no sistema
                </p>
              </div>

              <PlayersTable players={players} />
            </div>
          </div>
        </div>
      </div>
    </>
  )
}
