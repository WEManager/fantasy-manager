import type { Club } from '~/modules/core/types'

export interface Player {
  id: number
  know_as: string
  full_name: string
  age: number
  nation: {
    id: number
    name: string
    fifa_id: string
  }
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

export interface GameMessage {
  minute: number
  message: string
}

export interface Game {
  id: number
  hometeam: Club
  awayteam: Club
  hometeam_score: number
  awayteam_score: number
  start_time: string
  status: GameStatus
  group: TournamentGroup
  gameStatus: string
  CurrentMinute: number
  messages?: GameMessage[]
}

export type GameStatus = '0' | '1' | '2' | '3' | '4' | '5' | '6' | '7' | '8'

export interface GameShowData {
  game: Game
  hometeam: Record<string, Player>
  awayteam: Record<string, Player>
}
