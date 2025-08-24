import { usePage } from '@inertiajs/react'

interface User {
  id: number
  name: string
  isAdmin: boolean
  club?: {
    id: number
    name: string
  }
}

interface AuthProps {
  auth: {
    user: User | null
  }
}

export function useAuth() {
  const { auth } = usePage<AuthProps>().props

  return {
    user: auth.user,
    isAuthenticated: !!auth.user,
    isAdmin: auth.user?.isAdmin || false,
    hasClub: !!auth.user?.club,
  }
}
