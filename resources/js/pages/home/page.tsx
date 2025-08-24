import { Link, usePage } from '@inertiajs/react'

import { Button } from '~/modules/core/components/ui/button'
import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'
import { AppLayout } from '~/modules/layouts/app'

import { OngoingGamesSection } from './components/ongoing-games-section'
import { TournamentsSection } from './components/tournaments-section'

interface User {
  id: number
  name: string
  isAdmin: boolean
  club?: {
    id: number
    name: string
  }
}

interface PageProps {
  auth: {
    user: User | null
  }
  flash?: {
    message?: string
  }
  [key: string]: any
}

export default function Home() {
  const { auth } = usePage<PageProps>().props

  return (
    <AppLayout>
      <div className="container">
        <TournamentsSection />
        <OngoingGamesSection />

        {auth.user && !auth.user.club && (
          <Card className="mb-6">
            <CardHeader>
              <CardTitle>Available Clubs</CardTitle>
            </CardHeader>
            <CardContent>
              {/* Available clubs list will be added here */}
              <p>No clubs available at the moment.</p>
            </CardContent>
          </Card>
        )}

        {auth.user?.isAdmin && (
          <>
            <div className="row mb-4">
              <div className="col-sm-6">
                <Button asChild>
                  <Link href="/players/create">New Manager</Link>
                </Button>
              </div>
              <div className="col-sm-6">
                <Button asChild variant="outline">
                  <Link href="/players">Managers</Link>
                </Button>
              </div>
            </div>

            <div className="row">
              <div className="col-sm-6">
                <Button asChild>
                  <Link href="/tournaments/create">New Tournament</Link>
                </Button>
              </div>
              <div className="col-sm-6">
                <Button asChild variant="outline">
                  <Link href="/tournaments">Tournaments</Link>
                </Button>
              </div>
            </div>
          </>
        )}
      </div>
    </AppLayout>
  )
}
