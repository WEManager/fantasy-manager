import type { Player } from '../types'

interface PlayerHeaderProps {
  player: Player
}

export function PlayerHeader({ player }: PlayerHeaderProps) {
  const { club } = player

  return (
    <div className="w-full py-8" style={{ backgroundColor: club.colors[0] }}>
      <div className="container mx-auto px-4">
        <div className="flex flex-col lg:flex-row items-start lg:items-center gap-6">
          {/* Informações do Jogador */}
          <div className="flex-1">
            <h1
              className="text-3xl lg:text-4xl font-bold mb-4"
              style={{
                color: club.colors[1],
                textShadow: `1px 1px 0 ${club.colors[2]}, -1px 1px 0 ${club.colors[2]}, 1px -1px 0 ${club.colors[2]}, -1px -1px 0 ${club.colors[2]}`,
              }}
            >
              {player.know_as} ({player.full_name})
            </h1>

            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
              <div className="flex items-center gap-2">
                {/* <FlagIcon nationality={player.nation.fifa_id} /> */}
                <span>{player.nation.name}</span>
              </div>

              <div>Contrato com {player.club.name}</div>

              <div>
                {Math.floor(
                  (Date.now() - new Date(player.birth_date).getTime()) /
                    (1000 * 60 * 60 * 24 * 365.25),
                )}{' '}
                anos ({new Date(player.birth_date).toLocaleDateString('pt-BR')})
              </div>

              <div>
                {player.contract.wage} por mês até{' '}
                {new Date(player.contract.until).toLocaleDateString('pt-BR')}
              </div>
            </div>
          </div>

          {/* Informações Físicas */}
          <div className="bg-white/10 rounded-lg p-4 backdrop-blur-sm">
            <div className="text-center">
              <div className="text-2xl font-bold" style={{ color: club.colors[1] }}>
                {player.length}cm
              </div>
              <div className="text-sm opacity-80" style={{ color: club.colors[1] }}>
                Altura
              </div>
            </div>
            <div className="text-center mt-2">
              <div className="text-2xl font-bold" style={{ color: club.colors[1] }}>
                {player.weight}kg
              </div>
              <div className="text-sm opacity-80" style={{ color: club.colors[1] }}>
                Peso
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  )
}
