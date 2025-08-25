import type { ClubShowData } from '~/modules/clubs/types'

import { ClubHeader } from '~/modules/clubs/components/club-header'
import { GamesTable } from '~/modules/clubs/components/games-table'
import { SideMenu } from '~/modules/clubs/components/side-menu'

export default function ClubShowPage({ club }: ClubShowData) {
  return (
    <>
      <ClubHeader club={club} />

      <div className="container mx-auto px-4 py-8">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-8">
          {/* Conteúdo Principal */}
          <div className="lg:col-span-2 space-y-6">
            <GamesTable
              games={club.homegames}
              title={`Jogos em Casa do ${club.name}`}
              isHomeGames={true}
            />

            <GamesTable
              games={club.awaygames}
              title={`Jogos Fora do ${club.name}`}
              isHomeGames={false}
            />
          </div>

          {/* Menu Lateral */}
          <div className="lg:col-span-1">
            <SideMenu club={club} />
          </div>
        </div>
      </div>
    </>
  )
}
