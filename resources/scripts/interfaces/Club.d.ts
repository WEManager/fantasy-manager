import { Game } from './Game'
import { Player } from './Player'

export interface Club {
  id: number
  name: string
  slug: string
  colors: string[]
  locale: string
  reputation: number
  created_at: string
  updated_at: string
  players?: Player[]
  games?: Game[]
}
