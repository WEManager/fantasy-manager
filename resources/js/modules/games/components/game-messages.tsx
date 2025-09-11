import type { GameMessage } from '../types'

import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'

interface GameMessagesProps {
  messages: GameMessage[]
}

// Função para obter o ícone baseado no tipo de ação
function getActionIcon(actionType?: string, whistleType?: string): string {
  // Ícones especiais para apitos do juiz
  if (whistleType) {
    switch (whistleType) {
      case 'start':
        return '🔊'
      case 'halftime':
        return '⏱️'
      case 'second_half_start':
        return '🔊'
      case 'full_time':
        return '🏁'
      default:
        return '🔊'
    }
  }

  // Ícones normais para ações do jogo
  switch (actionType) {
    case 'goal':
      return '⚽'
    case 'dribble':
      return '🏃'
    case 'pass':
      return '📤'
    case 'defense':
      return '🛡️'
    case 'recovery':
      return '🔄'
    default:
      return '⚡'
  }
}

// Função para obter nome do período abreviado
function getPeriodAbbreviation(period?: string): string {
  switch (period) {
    case 'start':
      return 'INÍCIO'
    case 'first':
      return '1T'
    case 'first_extra':
      return '1T+'
    case 'second_start':
      return '2T'
    case 'second':
      return '2T'
    case 'second_extra':
      return '2T+'
    default:
      return ''
  }
}

export function GameMessages({ messages }: GameMessagesProps) {
  // Verificação de segurança para messages
  const safeMessages = messages || []

  // Ordenar todas as mensagens por minuto (sem agrupamento por período)
  const sortedMessages = safeMessages.sort((a, b) => a.minute - b.minute)

  return (
    <Card>
      <CardHeader>
        <CardTitle className="text-lg">Eventos do Jogo</CardTitle>
      </CardHeader>
      <CardContent>
        {sortedMessages.length > 0 ? (
          <div className="space-y-3">
            {sortedMessages.map((message, index) => (
              <div
                key={`${message.minute}-${index}`}
                className="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition-shadow"
              >
                {/* Cabeçalho do card com timestamp */}
                <div className="flex items-center gap-2 mb-2">
                  <span className="text-sm font-semibold text-gray-700">{message.minute}'</span>
                  <span className="text-xs text-gray-500">
                    {getPeriodAbbreviation(message.period)}
                  </span>
                </div>

                {/* Conteúdo principal do evento */}
                <div className="flex items-start gap-3">
                  {/* Ícone da ação */}
                  <div className="flex-shrink-0 text-xl">
                    {getActionIcon(message.action_type, message.whistle_type)}
                  </div>

                  {/* Texto do evento */}
                  <div className="flex-1">
                    <p className="text-sm text-gray-800 leading-relaxed">{message.message}</p>

                    {/* Informações dos jogadores se disponível */}
                    {(message.attacking_player || message.defending_player) && (
                      <div className="mt-2 flex items-center gap-2 text-xs text-gray-600">
                        {message.attacking_player && (
                          <span className="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 rounded-md">
                            <span>👤</span>
                            <span className="font-medium">{message.attacking_player}</span>
                          </span>
                        )}
                        {message.attacking_player && message.defending_player && (
                          <span className="text-gray-400">vs</span>
                        )}
                        {message.defending_player && (
                          <span className="inline-flex items-center gap-1 px-2 py-1 bg-red-50 rounded-md">
                            <span>🛡️</span>
                            <span className="font-medium">{message.defending_player}</span>
                          </span>
                        )}
                      </div>
                    )}
                  </div>
                </div>
              </div>
            ))}
          </div>
        ) : (
          <div className="text-center py-8 text-gray-500">
            <p>Nenhum evento registrado ainda.</p>
          </div>
        )}
      </CardContent>
    </Card>
  )
}
