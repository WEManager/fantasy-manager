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
    nationality: string
  }
}

export interface OngoingGame {
  id: number
  hometeam: Club
  awayteam: Club
  hometeam_score: number
  awayteam_score: number
  start_time: string
  status: GameStatus
  group: TournamentGroup
  gameStatus: string
}

export type GameStatus = '0' | '1' | '2' | '3' | '4' | '5' | '6' | '7' | '8'
