import { Club } from './Club'
import { Contract } from './Contratc'
import { Nation } from './Nation'

export interface Technical {
  corners: number
  crossing: number
  dribbling: number
  finishing: number
  first_touch: number
  freekick_taking: number
  heading: number
  long_shots: number
  long_throws: number
  marking: number
  passing: number
  penalty_taking: number
  tackling: number
  technique: number
}

export interface Mental {
  aggression: number
  anticipation: number
  bravery: number
  composure: number
  concentration: number
  decisions: number
  determination: number
  flair: number
  leadership: number
  off_the_ball: number
  positioning: number
  teamwork: number
  vision: number
  work_rate: number
}

export interface Physical {
  acceleration: number
  agility: number
  balance: number
  jumping_reach: number
  natural_fitness: number
  pace: number
  stamina: number
  strength: number
}

export interface Goalkeeping {
  aerial_reach: number
  command_of_area: number
  communication: number
  eccentricity: number
  handling: number
  kicking: number
  one_on_ones: number
  reflexes: number
  rushing_out: number
  tendency_to_punch: number
  throwing: number
}
export interface Player extends Goalkeeping, Physical, Mental {
  id: number
  full_name: string
  know_as: string
  fifa_player_id: string
  weak_foot: number
  skill_moves: number
  image_url: string
  preferred_foot: string
  age: number
  form: number
  length: number
  weight: number
  created_at: string
  updated_at: string
  nation?: Nation
  contract?: Contract
  club?: Club
  technical?: Technical
  mental?: Mental
  physical?: Physical
  goalkeeping?: Goalkeeping
}
