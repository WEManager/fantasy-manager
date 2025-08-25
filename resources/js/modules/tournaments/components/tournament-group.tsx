import { slugify } from '~/modules/core/utils/string'

import { FixturesByDate } from './fixtures-by-date'
import { StandingsTable } from './standings-table'

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
  club: {
    id: number
    name: string
    slug: string
  }
}

interface TournamentGroupProps {
  id: number
  name: string
  standings: Standing[]
  positionStatus?: string[]
  fixtures?: any[]
  selectedDate?: string
  loading?: boolean
}

export function TournamentGroup({
  id,
  name,
  standings,
  positionStatus = [],
  fixtures = [],
  selectedDate,
  loading = false,
}: TournamentGroupProps) {
  return (
    <div className="space-y-8" id={slugify(name)}>
      <div>
        <h3 className="text-2xl font-bold text-gray-900 dark:text-white mb-4">{name}</h3>
      </div>

      <FixturesByDate
        groupId={id}
        fixtures={fixtures}
        selectedDate={selectedDate}
        loading={loading}
      />

      <div>
        <h4 className="text-lg font-semibold text-gray-900 dark:text-white mb-4">Classificação</h4>
        <StandingsTable standings={standings} positionStatus={positionStatus} />
      </div>
    </div>
  )
}
