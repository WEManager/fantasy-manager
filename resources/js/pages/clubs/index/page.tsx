import type { ClubIndexData } from '~/modules/clubs/types'

import { Head } from '@inertiajs/react'

import { ClubsList } from '~/modules/clubs/components/clubs-list'

export default function ClubsIndexPage({ response }: ClubIndexData) {
  return (
    <>
      <Head title="Clubes" />

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div className="p-6 bg-white border-b border-gray-200">
              <div className="mb-6">
                <h1 className="text-2xl font-bold text-gray-900 mb-2">Lista de Clubes</h1>
                <p className="text-sm text-gray-600">
                  Visualize todos os clubes disponíveis no sistema
                </p>
              </div>

              <ClubsList clubs={response.data} />

              {/* Paginação pode ser adicionada aqui se necessário */}
            </div>
          </div>
        </div>
      </div>
    </>
  )
}
