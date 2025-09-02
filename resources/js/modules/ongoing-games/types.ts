import type { FixtureStatus } from '../tournaments/types'

export interface Club {
  id: number
  name: string
  colors: string[]
  slug: string
}

export interface TournamentGroup {
  id: number
  name: string
  tournament: {
    id: number
    name: string
    slug: string
  }
}

export interface OngoingGame {
  id: number
  hometeam: Club
  awayteam: Club
  hometeam_score: number
  awayteam_score: number
  start_time: string
  status: FixtureStatus
  group: TournamentGroup
  gameStatus: string
}
