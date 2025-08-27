import type { Club } from '~/modules/core/types'

export interface Player {
  id: number
  know_as: string
  full_name: string
  age: number
  selected_position?: string
}

export interface Lineup {
  id: number
  club_id: number
  team: string
  player_1?: number
  player_2?: number
  player_3?: number
  player_4?: number
  player_5?: number
  player_6?: number
  player_7?: number
  player_8?: number
  player_9?: number
  player_10?: number
  player_11?: number
  position_1?: string
  position_2?: string
  position_3?: string
  position_4?: string
  position_5?: string
  position_6?: string
  position_7?: string
  position_8?: string
  position_9?: string
  position_10?: string
  position_11?: string
  substitute_1?: number
  substitute_2?: number
  substitute_3?: number
  substitute_4?: number
  substitute_5?: number
  substitute_6?: number
  created_at: string
  updated_at: string
}

export interface LineupEditData {
  club: Club
  lineup: Lineup
  players: Player[]
  squad: string
}

export type Position =
  | 'GK'
  | 'LD'
  | 'CLD'
  | 'CD'
  | 'CRD'
  | 'RD'
  | 'LM'
  | 'CLM'
  | 'CM'
  | 'CRM'
  | 'RM'
  | 'LF'
  | 'CLF'
  | 'CF'
  | 'CRF'
  | 'RF'
  | 'SUB_1'
  | 'SUB_2'
  | 'SUB_3'
  | 'SUB_4'
  | 'SUB_5'
  | 'SUB_6'

export const POSITIONS: Position[] = [
  'GK',
  'LD',
  'CLD',
  'CD',
  'CRD',
  'RD',
  'LM',
  'CLM',
  'CM',
  'CRM',
  'RM',
  'LF',
  'CLF',
  'CF',
  'CRF',
  'RF',
]

export const SUBSTITUTE_POSITIONS: Position[] = [
  'SUB_1',
  'SUB_2',
  'SUB_3',
  'SUB_4',
  'SUB_5',
  'SUB_6',
]
