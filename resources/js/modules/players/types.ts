import type { Club } from '~/modules/core/types'

export interface Nation {
  id: number
  name: string
  fifa_id: string
}

export interface Contract {
  id: number
  wage: number
  until: string
  club_id: number
}

export interface Player {
  id: number
  know_as: string
  full_name: string
  birth_date: string
  length: number
  weight: number
  nation_fifa_id: string
  nation: Nation
  club: Club
  contract: Contract
  stats: {
    raw: Record<string, number>
    mental: Record<string, number | null>
    physical: Record<string, number | null>
    technical: Record<string, number | null>
    goalkeeping: Record<string, number | null>
  }
}

export interface PlayerIndexData {
  players: Player[]
}

export interface PlayerShowData {
  player: Player
}
