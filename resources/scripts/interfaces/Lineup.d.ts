import { Player } from './Player'

export interface Lineup {
  id: number
  club_id: number
  position_1: string
  player_1: Player
  position_2: string
  player_2: Player
  position_3: string
  player_3: Player
  position_4: string
  player_4: Player
  position_5: string
  player_5: Player
  position_6: string
  player_6: Player
  position_7: string
  player_7: Player
  position_8: string
  player_8: Player
  position_9: string
  player_9: Player
  position_10: string
  player_10: Player
  position_11: string
  player_11: Player
  substitute_1: Player
  substitute_2: Player
  substitute_3: Player
  substitute_4: Player
  substitute_5: Player
  substitute_6: Player
  created_at: Date
  updated_at: Date
}
