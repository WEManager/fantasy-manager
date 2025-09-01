export interface Club {
  id: number
  name: string
  slug: string
  colors: string[]
  manager?: {
    id: number
    name: string
  }
}

export interface AvailableClub extends Club {
  hasManager: boolean
  canApply: boolean
}
