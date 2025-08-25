import type { Club } from '~/modules/core/types'

export interface TournamentGame {
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
    homegames: TournamentGame[]
    awaygames: TournamentGame[]
    players?: any[]
  }
}
