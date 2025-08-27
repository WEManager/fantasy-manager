import type { Club } from '~/modules/core/types'

import { useId } from 'react'
import { useForm } from '@inertiajs/react'

import { Button } from '~/modules/core/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'
import { Input } from '~/modules/core/components/ui/input'
import { Label } from '~/modules/core/components/ui/label'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '~/modules/core/components/ui/select'

interface TournamentFormProps {
  clubs: Club[]
}

export function TournamentForm({ clubs }: TournamentFormProps) {
  const { data, setData, post, processing, errors } = useForm({
    name: '',
    type: '',
    groups: 1,
    playoffs: 0,
    proceeding_to_playoffs: 2,
    selectedClubs: [] as number[],
  })

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    post(route('tournament.store'))
  }

  return (
    <Card>
      <CardHeader>
        <CardTitle>Criar Novo Torneio</CardTitle>
      </CardHeader>
      <CardContent>
        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Nome do Torneio */}
          <div className="space-y-2">
            <Label htmlFor="name">Nome do Torneio</Label>
            <Input
              id={useId()}
              type="text"
              value={data.name}
              onChange={(e) => setData('name', e.target.value)}
              placeholder="Digite o nome do torneio"
            />
            {errors.name && <p className="text-sm text-red-600">{errors.name}</p>}
          </div>

          {/* Tipo de Competição */}
          <div className="space-y-2">
            <Label htmlFor="type">Tipo de Competição</Label>
            <Select value={data.type} onValueChange={(value) => setData('type', value)}>
              <SelectTrigger>
                <SelectValue placeholder="Selecione o tipo" />
              </SelectTrigger>
              <SelectContent>
                <SelectItem value="league">Liga</SelectItem>
                <SelectItem value="cup">Copa</SelectItem>
                <SelectItem value="championship">Campeonato</SelectItem>
              </SelectContent>
            </Select>
            {errors.type && <p className="text-sm text-red-600">{errors.type}</p>}
          </div>

          {/* Número de Grupos */}
          <div className="space-y-2">
            <Label htmlFor="groups">Número de Grupos</Label>
            <Input
              id={useId()}
              type="number"
              min="1"
              max="8"
              value={data.groups}
              onChange={(e) => setData('groups', parseInt(e.target.value, 10))}
            />
            {errors.groups && <p className="text-sm text-red-600">{errors.groups}</p>}
          </div>

          {/* Playoffs */}
          <div className="space-y-2">
            <Label htmlFor="playoffs">Playoffs</Label>
            <Input
              id={useId()}
              type="number"
              min="0"
              max="16"
              value={data.playoffs}
              onChange={(e) => setData('playoffs', parseInt(e.target.value, 10))}
            />
            {errors.playoffs && <p className="text-sm text-red-600">{errors.playoffs}</p>}
          </div>

          {/* Times Classificados para Playoffs */}
          <div className="space-y-2">
            <Label htmlFor="proceeding_to_playoffs">Times Classificados para Playoffs</Label>
            <Input
              id={useId()}
              type="number"
              min="1"
              max="8"
              value={data.proceeding_to_playoffs}
              onChange={(e) => setData('proceeding_to_playoffs', parseInt(e.target.value, 10))}
            />
            {errors.proceeding_to_playoffs && (
              <p className="text-sm text-red-600">{errors.proceeding_to_playoffs}</p>
            )}
          </div>

          {/* Seleção de Clubes */}
          <div className="space-y-2">
            <Label>Selecionar Clubes</Label>
            <div className="grid grid-cols-2 md:grid-cols-3 gap-4 max-h-60 overflow-y-auto border rounded-lg p-4">
              {clubs.map((club) => (
                <div key={club.id} className="flex items-center space-x-2">
                  <input
                    type="checkbox"
                    id={`club-${club.id}`}
                    checked={data.selectedClubs.includes(club.id)}
                    onChange={(e) => {
                      if (e.target.checked) {
                        setData('selectedClubs', [...data.selectedClubs, club.id])
                      } else {
                        setData(
                          'selectedClubs',
                          data.selectedClubs.filter((id) => id !== club.id),
                        )
                      }
                    }}
                    className="rounded"
                  />
                  <Label htmlFor={`club-${club.id}`} className="text-sm">
                    {club.name}
                  </Label>
                </div>
              ))}
            </div>
            {errors.selectedClubs && <p className="text-sm text-red-600">{errors.selectedClubs}</p>}
          </div>

          {/* Botão de Envio */}
          <Button type="submit" disabled={processing} className="w-full">
            {processing ? 'Criando...' : 'Criar Torneio'}
          </Button>
        </form>
      </CardContent>
    </Card>
  )
}
