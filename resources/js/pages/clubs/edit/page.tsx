import type { ClubEditData } from '~/modules/clubs/types'

import { Head } from '@inertiajs/react'

import { ClubForm } from '~/modules/clubs/components/club-form'

export default function ClubEditPage({ club }: ClubEditData) {
  return (
    <>
      <Head title={`Editar ${club.name}`} />

      <div className="py-12">
        <div className="max-w-2xl mx-auto sm:px-6 lg:px-8">
          <div className="mb-6">
            <h1 className="text-2xl font-bold text-gray-900 mb-2">Editar Clube</h1>
            <p className="text-sm text-gray-600">Modifique as informações do clube {club.name}</p>
          </div>

          <ClubForm club={club} />
        </div>
      </div>
    </>
  )
}
