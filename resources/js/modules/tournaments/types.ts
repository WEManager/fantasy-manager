import type { Club } from '~/modules/core/types'

export interface Tournament {
  id: number
  name: string
  slug: string
  type: string
  groups: number
  playoffs: number
  proceeding_to_playoffs: number
  created_at: string
  updated_at: string
  status: string
  start_date: string
  end_date: string
  tournamentGroups?: TournamentGroup[]
}

export interface TournamentGroup {
  id: number
  name: string
  standings: TournamentStanding[]
}

export interface TournamentStanding {
  id: number
  club_id: number
  group_id: number
  points: number
  played: number
  won: number
  drawn: number
  lost: number
  scored: number
  conceded: number
  club: Club
}

export interface TournamentFixture {
  id: number
  hometeam_id: number
  awayteam_id: number
  hometeam_score: number
  awayteam_score: number
  start_time: string
  status: string
  gameStatus: string
  hometeam: Club
  awayteam: Club
}

export interface TournamentIndexData {
  tournaments: Tournament[]
}

export interface TournamentCreateData {
  clubs: Club[]
}

export interface TournamentShowData {
  tournament: Tournament
  participating_clubs: Record<number, Club>
  position_status: string[]
  fixtures: TournamentFixture[]
  selectedDate: string
}
