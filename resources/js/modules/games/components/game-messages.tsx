import type { GameMessage } from '../types'

import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'

interface GameMessagesProps {
  messages: GameMessage[]
}

export function GameMessages({ messages }: GameMessagesProps) {
  // Verificação de segurança para messages
  const safeMessages = messages || []

  return (
    <Card>
      <CardHeader>
        <CardTitle className="text-lg">Eventos do Jogo</CardTitle>
      </CardHeader>
      <CardContent>
        {safeMessages.length > 0 ? (
          <div className="space-y-2">
            {safeMessages.map((message) => (
              <div
                key={message.minute}
                className="flex items-start gap-3 p-3 bg-gray-50 rounded-lg"
              >
                <div className="flex-shrink-0 w-12 text-center">
                  <span className="text-sm font-bold text-blue-600">{message.minute}'</span>
                </div>
                <div className="flex-1">
                  <p className="text-sm">{message.message}</p>
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
