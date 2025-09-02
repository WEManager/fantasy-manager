import type { Club } from '../core/types'

export type TournamentType = 'league' | 'groups' | 'championship'

export const FixtureTypes = {
  REGULAR_TIME_ONLY: 1,
  REGULAR_TIME_PLUS_PENALTIES: 2,
  EXTRA_TIME_PLUS_PENALTIES: 3,
}

export type FixtureType = (typeof FixtureTypes)[keyof typeof FixtureTypes]

export const FixtureStatuses = {
  NOT_STARTED: 0,
  ACTIVE: 1,
  ENDED: 2,
  WAITING_FOR_SECOND_HALF: 3,
  WAITING_FOR_EXTRA_TIME: 4,
  WAITING_FOR_PENALTIES: 5,
  CANCELLED: 6,
  POSTPONED: 7,
  NOT_DECIDED: 8,
}

export type FixtureStatus = (typeof FixtureStatuses)[keyof typeof FixtureStatuses]

export interface Tournament {
  id: number
  name: string
  slug: string
  type: TournamentType
  groups: number
  playoffs: boolean
  proceeding_to_playoffs: number
  created_at: string
  updated_at: string
  status: string
  start_date: string
  end_date: string
  tournamentGroups?: TournamentGroup[]
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
  type: FixtureType
  status: FixtureStatus
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
