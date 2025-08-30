import type { Club } from '~/modules/core/types'

export interface ManagerContract {
  id: number
  user_id: number
  club_id: number
  from: string
  until: string
  wage: number
  status: 'ongoing' | 'manager_resigned' | 'club_fired'
  created_at: string
  updated_at: string
}

export interface JobApplication {
  id: number
  user_id: number
  club_id: number
  status: 'pending' | 'approved' | 'rejected'
  created_at: string
  updated_at: string
}

export interface QuitPageData {
  club: Club
  boardMessage: string
}

export interface ApplyPageData {
  club: Club
}

export interface ApplyDeniedPageData {
  club: Club
  job_application: JobApplication
}
