import type { Lineup, Player } from '../types'

import { useForm } from '@inertiajs/react'

import { Button } from '~/modules/core/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from '~/modules/core/components/ui/select'

import { POSITIONS } from '../types'

interface LineupEditorProps {
  lineup: Lineup
  players: Player[]
}

export function LineupEditor({ lineup, players }: LineupEditorProps) {
  const { data, setData, post, processing, errors } = useForm({
    player_1: lineup.player_1 || null,
    player_2: lineup.player_2 || null,
    player_3: lineup.player_3 || null,
    player_4: lineup.player_4 || null,
    player_5: lineup.player_5 || null,
    player_6: lineup.player_6 || null,
    player_7: lineup.player_7 || null,
    player_8: lineup.player_8 || null,
    player_9: lineup.player_9 || null,
    player_10: lineup.player_10 || null,
    player_11: lineup.player_11 || null,
    position_1: lineup.position_1 || null,
    position_2: lineup.position_2 || null,
    position_3: lineup.position_3 || null,
    position_4: lineup.position_4 || null,
    position_5: lineup.position_5 || null,
    position_6: lineup.position_6 || null,
    position_7: lineup.position_7 || null,
    position_8: lineup.position_8 || null,
    position_9: lineup.position_9 || null,
    position_10: lineup.position_10 || null,
    position_11: lineup.position_11 || null,
    substitute_1: lineup.substitute_1 || null,
    substitute_2: lineup.substitute_2 || null,
    substitute_3: lineup.substitute_3 || null,
    substitute_4: lineup.substitute_4 || null,
    substitute_5: lineup.substitute_5 || null,
    substitute_6: lineup.substitute_6 || null,
  })

  const handleSubmit = (e: React.FormEvent) => {
    e.preventDefault()
    post(route('update_lineup', { lineup: lineup.id }))
  }

  return (
    <Card>
      <CardHeader>
        <CardTitle>Editar Escalação</CardTitle>
      </CardHeader>
      <CardContent>
        <form onSubmit={handleSubmit} className="space-y-6">
          {/* Jogadores Titulares */}
          <div className="space-y-4">
            <h3 className="text-lg font-semibold">Titulares</h3>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              {Array.from({ length: 11 }, (_, i) => i + 1).map((slot) => (
                <div key={slot} className="space-y-2">
                  <div className="text-sm font-medium">Jogador {slot}</div>
                  <Select
                    value={data[`player_${slot}` as keyof typeof data]?.toString() || 'none'}
                    onValueChange={(value) =>
                      setData(
                        `player_${slot}` as keyof typeof data,
                        value === 'none' ? null : (parseInt(value) as any),
                      )
                    }
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Selecione um jogador" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="none">Nenhum</SelectItem>
                      {players.map((player) => (
                        <SelectItem key={player.id} value={player.id.toString()}>
                          {player.know_as}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>

                  <Select
                    value={data[`position_${slot}` as keyof typeof data] || 'none'}
                    onValueChange={(value) =>
                      setData(
                        `position_${slot}` as keyof typeof data,
                        value === 'none' ? null : value,
                      )
                    }
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Selecione uma posição" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="none">Nenhuma</SelectItem>
                      {POSITIONS.map((position) => (
                        <SelectItem key={position} value={position}>
                          {position}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
              ))}
            </div>
          </div>

          {/* Reservas */}
          <div className="space-y-4">
            <h3 className="text-lg font-semibold">Reservas</h3>
            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              {Array.from({ length: 6 }, (_, i) => i + 1).map((slot) => (
                <div key={slot} className="space-y-2">
                  <div className="text-sm font-medium">Reserva {slot}</div>
                  <Select
                    value={data[`substitute_${slot}` as keyof typeof data]?.toString() || 'none'}
                    onValueChange={(value) =>
                      setData(
                        `substitute_${slot}` as keyof typeof data,
                        value === 'none' ? null : (parseInt(value) as any),
                      )
                    }
                  >
                    <SelectTrigger>
                      <SelectValue placeholder="Selecione um jogador" />
                    </SelectTrigger>
                    <SelectContent>
                      <SelectItem value="none">Nenhum</SelectItem>
                      {players.map((player) => (
                        <SelectItem key={player.id} value={player.id.toString()}>
                          {player.know_as}
                        </SelectItem>
                      ))}
                    </SelectContent>
                  </Select>
                </div>
              ))}
            </div>
          </div>

          {/* Botão de Envio */}
          <Button type="submit" disabled={processing} className="w-full">
            {processing ? 'Salvando...' : 'Salvar Escalação'}
          </Button>
        </form>
      </CardContent>
    </Card>
  )
}
