import type { SharedData } from '~/types/shared'

import { Head } from '@inertiajs/react'

interface DashboardProps extends SharedData {
  // Adicione props específicas do dashboard aqui se necessário
}

export default function DashboardPage(_props: DashboardProps) {
  return (
    <>
      <Head title="Dashboard" />

      <div className="py-12">
        <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
          <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div className="p-6 bg-white border-b border-gray-200">
              <h1 className="text-2xl font-bold text-gray-900 mb-4">Dashboard</h1>
              <p className="text-gray-600">Você está logado!</p>
            </div>
          </div>
        </div>
      </div>
    </>
  )
}
