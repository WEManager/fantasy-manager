import type { Club } from '~/modules/core/types'
import type { Player } from '~/modules/players/types'

export interface Fixture {
  id: number
  hometeam_id: number
  awayteam_id: number
  hometeam_score?: number
  awayteam_score?: number
  status: number
  gameStatus: string
  group: {
    id: number
    name: string
    tournament: {
      id: number
      name: string
      slug: string
    }
  }
  hometeam: Club
  awayteam: Club
}

export interface ClubShowData {
  club: Club & {
    homegames: Fixture[]
    awaygames: Fixture[]
    players?: Player[]
  }
}

export interface ClubIndexData {
  response: {
    data: Club[]
    current_page: number
    last_page: number
    per_page: number
    total: number
  }
}

export interface ClubEditData {
  club: Club
}
