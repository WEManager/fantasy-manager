import type { Club } from '~/modules/core/types'

import { useId } from 'react'
import { useForm } from '@inertiajs/react'

import { Button } from '~/modules/core/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'
import { Input } from '~/modules/core/components/ui/input'
import { Label } from '~/modules/core/components/ui/label'

interface ContractFormProps {
  club: Club
}

export function ContractForm({ club }: ContractFormProps) {
  const { data, setData, post, processing, errors } = useForm({
    club_id: club.id,
    from: new Date().toISOString().split('T')[0],
    until: new Date(Date.now() + 365 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
    wage: 1000,
  })

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    post(route('club.apply.store'))
  }

  return (
    <Card>
      <CardHeader>
        <CardTitle>Candidatura para {club.name}</CardTitle>
      </CardHeader>
      <CardContent>
        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Data de Início */}
          <div className="space-y-2">
            <Label htmlFor="from">Data de Início</Label>
            <Input
              id={useId()}
              type="date"
              value={data.from}
              onChange={(e) => setData('from', e.target.value)}
            />
            {errors.from && <p className="text-sm text-red-600">{errors.from}</p>}
          </div>

          {/* Data de Término */}
          <div className="space-y-2">
            <Label htmlFor="until">Data de Término</Label>
            <Input
              id={useId()}
              type="date"
              value={data.until}
              onChange={(e) => setData('until', e.target.value)}
            />
            {errors.until && <p className="text-sm text-red-600">{errors.until}</p>}
          </div>

          {/* Salário */}
          <div className="space-y-2">
            <Label htmlFor="wage">Salário Mensal (€)</Label>
            <Input
              id={useId()}
              type="number"
              value={data.wage}
              onChange={(e) => setData('wage', parseInt(e.target.value))}
              placeholder="1000"
              min="100"
            />
            {errors.wage && <p className="text-sm text-red-600">{errors.wage}</p>}
          </div>

          {/* Botão de Envio */}
          <Button type="submit" disabled={processing} className="w-full">
            {processing ? 'Enviando candidatura...' : 'Enviar Candidatura'}
          </Button>
        </form>
      </CardContent>
    </Card>
  )
}
