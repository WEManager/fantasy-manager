import { Club } from './Club'
import { Lineup } from './Lineup'
import { Group } from './Tournament'

interface GameMessage {
  minute: number
  message: string
}
export interface Game {
  id: number
  hometeam_score: number | null
  awayteam_score: number | null
  start_time: string
  type: string
  status: string
  home_lineup: Lineup
  away_lineup: Lineup
  home_team: Club
  away_team: Club
  group: any
  game_status: string
  messages: GameMessage[]
  group: Group
}
