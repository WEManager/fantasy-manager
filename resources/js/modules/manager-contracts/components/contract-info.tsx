import type { ManagerContract } from '../types'

import { Badge } from '~/modules/core/components/ui/badge'
import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'

interface ContractInfoProps {
  contract: ManagerContract
}

export function ContractInfo({ contract }: ContractInfoProps) {
  const getStatusBadge = (status: ManagerContract['status']) => {
    switch (status) {
      case 'ongoing':
        return <Badge variant="default">Ativo</Badge>
      case 'manager_resigned':
        return <Badge variant="destructive">Renunciado</Badge>
      case 'club_fired':
        return <Badge variant="destructive">Demitido</Badge>
      default:
        return <Badge variant="secondary">Desconhecido</Badge>
    }
  }

  const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('pt-BR')
  }

  const formatCurrency = (amount: number) => {
    return new Intl.NumberFormat('pt-BR', {
      style: 'currency',
      currency: 'EUR',
    }).format(amount)
  }

  return (
    <Card>
      <CardHeader>
        <CardTitle className="flex items-center justify-between">
          Informações do Contrato
          {getStatusBadge(contract.status)}
        </CardTitle>
      </CardHeader>
      <CardContent className="space-y-4">
        <div className="grid grid-cols-2 gap-4">
          <div>
            <p className="text-sm font-medium text-muted-foreground">Data de Início</p>
            <p className="text-sm">{formatDate(contract.from)}</p>
          </div>
          <div>
            <p className="text-sm font-medium text-muted-foreground">Data de Término</p>
            <p className="text-sm">{formatDate(contract.until)}</p>
          </div>
        </div>

        <div>
          <p className="text-sm font-medium text-muted-foreground">Salário Mensal</p>
          <p className="text-lg font-semibold">{formatCurrency(contract.wage)}</p>
        </div>

        <div>
          <p className="text-sm font-medium text-muted-foreground">Criado em</p>
          <p className="text-sm">{formatDate(contract.created_at)}</p>
        </div>
      </CardContent>
    </Card>
  )
}
