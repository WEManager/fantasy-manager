import type { ApplyPageData } from '~/modules/manager-contracts/types'

import { Head } from '@inertiajs/react'

import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '~/modules/core/components/ui/card'
import { ContractForm } from '~/modules/manager-contracts/components/contract-form'

export default function ApplyPage({ club }: ApplyPageData) {
  return (
    <div className="min-h-screen bg-background p-4">
      <Head title={`Aplicar para ${club.name}`} />

      <div className="container mx-auto max-w-4xl">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Main Content */}
          <div className="lg:col-span-2">
            <Card>
              <CardHeader>
                <CardTitle className="text-2xl">{club.name}</CardTitle>
                <CardDescription>
                  {club.manager ? `Gerente: ${club.manager.name}` : 'Sem gerente'}
                </CardDescription>
              </CardHeader>

              <CardContent>
                <ContractForm club={club} />
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </div>
  )
}
