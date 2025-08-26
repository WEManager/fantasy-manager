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
  age: number
  length: number
  weight: number
  nation_fifa_id: string
  nation: Nation
  club: Club
  contract: Contract
  technical: Record<string, number>
  mental: Record<string, number>
  physical: Record<string, number>
  goalkeeping: Record<string, number>
}

export interface PlayerIndexData {
  players: Player[]
}

export interface PlayerShowData {
  person: Player
}
