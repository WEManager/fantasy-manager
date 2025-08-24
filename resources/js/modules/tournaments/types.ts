export interface Tournament {
  id: number
  slug: string
  name: string
  nationality: string
  status: TournamentStatus
}

export type TournamentStatus = 'ACTIVE' | 'NOT_STARTED' | 'NOT_DECIDED' | 'FINISHED'

export interface TournamentsByNation {
  [nationality: string]: Tournament[]
}
