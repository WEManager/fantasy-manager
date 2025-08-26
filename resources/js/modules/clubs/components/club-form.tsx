import type { Club } from '~/modules/core/types'

import { useId } from 'react'
import { useForm } from '@inertiajs/react'

import { Button } from '~/modules/core/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'
import { Input } from '~/modules/core/components/ui/input'
import { Label } from '~/modules/core/components/ui/label'

interface ClubFormProps {
  club: Club
}

export function ClubForm({ club }: ClubFormProps) {
  const { data, setData, put, processing, errors } = useForm({
    name: club.name,
    locale: club.locale,
    mainColor: club.colors[0] || '#000000',
    secondColor: club.colors[1] || '#000000',
    thirdColor: club.colors[2] || '#000000',
  })

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    put(route('club.update', { club: club.slug }))
  }

  const handleColorChange = (field: string, value: string) => {
    setData(field, value)
  }

  return (
    <Card>
      <CardHeader>
        <CardTitle>Editar Clube</CardTitle>
      </CardHeader>
      <CardContent>
        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Nome do Clube */}
          <div className="space-y-2">
            <Label htmlFor="name">Nome do Clube</Label>
            <Input
              id={useId()}
              type="text"
              value={data.name}
              onChange={(e) => setData('name', e.target.value)}
              placeholder="Digite o nome do clube"
            />
            {errors.name && <p className="text-sm text-red-600">{errors.name}</p>}
          </div>

          {/* Localização */}
          <div className="space-y-2">
            <Label htmlFor="locale">Localização</Label>
            <Input
              id={useId()}
              type="text"
              value={data.locale}
              onChange={(e) => setData('locale', e.target.value)}
              placeholder="Ex: BRA, ARG, ENG"
            />
            {errors.locale && <p className="text-sm text-red-600">{errors.locale}</p>}
          </div>

          {/* Cores */}
          <div className="space-y-2">
            <Label>Cores do Clube</Label>
            <div className="grid grid-cols-3 gap-4">
              {/* Cor Principal */}
              <div className="space-y-2">
                <Label htmlFor="mainColor">Cor Principal</Label>
                <div className="flex items-center space-x-2">
                  <Input
                    id="mainColor"
                    type="color"
                    value={data.mainColor}
                    onChange={(e) => handleColorChange('mainColor', e.target.value)}
                    className="w-16 h-10 p-1"
                  />
                  <Input
                    type="text"
                    value={data.mainColor}
                    onChange={(e) => handleColorChange('mainColor', e.target.value)}
                    placeholder="#000000"
                  />
                </div>
              </div>

              {/* Segunda Cor */}
              <div className="space-y-2">
                <Label htmlFor="secondColor">Segunda Cor</Label>
                <div className="flex items-center space-x-2">
                  <Input
                    id="secondColor"
                    type="color"
                    value={data.secondColor}
                    onChange={(e) => handleColorChange('secondColor', e.target.value)}
                    className="w-16 h-10 p-1"
                  />
                  <Input
                    type="text"
                    value={data.secondColor}
                    onChange={(e) => handleColorChange('secondColor', e.target.value)}
                    placeholder="#000000"
                  />
                </div>
              </div>

              {/* Terceira Cor */}
              <div className="space-y-2">
                <Label htmlFor="thirdColor">Terceira Cor</Label>
                <div className="flex items-center space-x-2">
                  <Input
                    id="thirdColor"
                    type="color"
                    value={data.thirdColor}
                    onChange={(e) => handleColorChange('thirdColor', e.target.value)}
                    className="w-16 h-10 p-1"
                  />
                  <Input
                    type="text"
                    value={data.thirdColor}
                    onChange={(e) => handleColorChange('thirdColor', e.target.value)}
                    placeholder="#000000"
                  />
                </div>
              </div>
            </div>
            {(errors.mainColor || errors.secondColor || errors.thirdColor) && (
              <p className="text-sm text-red-600">Erro nas cores</p>
            )}
          </div>

          {/* Botão de Envio */}
          <Button type="submit" disabled={processing} className="w-full">
            {processing ? 'Salvando...' : 'Salvar Alterações'}
          </Button>
        </form>
      </CardContent>
    </Card>
  )
}
