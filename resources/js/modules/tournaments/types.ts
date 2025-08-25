import type { Club } from '~/modules/core/types'

interface Game {
  id: number
  // Adicione outras propriedades conforme necessário
}

interface Standing {
  id: number
  club_id: number
  group_id: number
  won: number
  tie: number
  lost: number
  scored: number
  conceded: number
  points: number
  created_at: string
  updated_at: string
  laravel_through_key: number
  club: Club
}

interface Group {
  id: number
  name: string
  upcoming_games: any[]
  games: Game[]
  standings: Standing[]
}

interface Qualification {
  id: number
  tournament_id: number
  season_id: number
  position: number
  qualified_for_id: number
  season_ended: number
  status: string
  qualification_date: string
  created_at: string
  updated_at: string
}

export interface Tournament {
  id: number
  name: string
  slug: string
  type: string
  groups: number
  tournamentGroups?: Group[]
  playoffs: number
  proceeding_to_playoffs: any
  created_at: string
  updated_at: string
  participants?: Club[]
  qualifications?: Qualification[]
  standings?: Standing[]
  status?: string
  start_date?: string
  end_date?: string
}

export type TournamentStatus = 'ACTIVE' | 'NOT_STARTED' | 'NOT_DECIDED' | 'FINISHED'

export interface TournamentsByNation {
  [nationality: string]: Tournament[]
}
