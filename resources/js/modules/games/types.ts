import type { Club } from '~/modules/core/types'
import type { FixtureStatus } from '../tournaments/types'

export interface Player {
  id: number
  know_as: string
  full_name: string
  position?: string
  slot?: number
  nation: {
    id: number
    name: string
    iso_code: string
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
  period?: string
  attacking_player?: string
  defending_player?: string
  attacking_player_id?: number
  defending_player_id?: number
  action_type?: string
  whistle_type?: string
  referee_action?: boolean
}

export interface Lineup {
  id: number
  club_id: number
  players: Record<string, Player>
}

export interface Game {
  id: number
  hometeam: Club
  awayteam: Club
  hometeam_score: number
  awayteam_score: number
  start_time: string
  status: FixtureStatus
  group: TournamentGroup
  gameStatus: string
  CurrentMinute: number
  messages?: GameMessage[]
  homeLineup?: Lineup
  awayLineup?: Lineup
}

export interface GameShowData {
  game: Game
}
