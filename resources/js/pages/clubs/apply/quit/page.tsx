import type { QuitPageData } from '~/modules/manager-contracts/types'

import { Head, Link } from '@inertiajs/react'

import { Button } from '~/modules/core/components/ui/button'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '~/modules/core/components/ui/card'

export default function QuitPage({ club, boardMessage }: QuitPageData) {
  return (
    <div className="min-h-screen bg-background p-4">
      <Head title={`Renúncia - ${club.name}`} />

      <div className="container mx-auto max-w-4xl">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Main Content */}
          <div className="lg:col-span-2">
            <Card>
              <CardHeader>
                <CardTitle className="text-2xl">
                  {club.name} ({club.locale})
                </CardTitle>
                <CardDescription>Solicitação de renúncia</CardDescription>
              </CardHeader>

              <CardContent>
                <div className="space-y-6">
                  <div className="p-4 rounded-md bg-orange-50 dark:bg-orange-900/20 border border-orange-200 dark:border-orange-800">
                    <p className="text-orange-700 dark:text-orange-400">{boardMessage}</p>
                  </div>

                  <div className="flex gap-4">
                    <Button asChild>
                      <Link href={route('home')}>Voltar ao início</Link>
                    </Button>

                    <Button asChild variant="outline">
                      <Link href={`/clubs/${club.id}`}>Ver clube</Link>
                    </Button>
                  </div>
                </div>
              </CardContent>
            </Card>
          </div>

          {/* Sidebar */}
          <div className="lg:col-span-1">
            <Card>
              <CardHeader>
                <CardTitle>Times do Clube</CardTitle>
              </CardHeader>
              <CardContent>
                <nav className="space-y-2">
                  <a
                    href={`/clubs/${club.id}/senior`}
                    className="block text-sm text-muted-foreground hover:text-foreground transition-colors"
                  >
                    Senior
                  </a>
                  <a
                    href={`/clubs/${club.id}/u21`}
                    className="block text-sm text-muted-foreground hover:text-foreground transition-colors"
                  >
                    Reserv / U21
                  </a>
                  <a
                    href={`/clubs/${club.id}/u19`}
                    className="block text-sm text-muted-foreground hover:text-foreground transition-colors"
                  >
                    U19
                  </a>
                </nav>
              </CardContent>
            </Card>
          </div>
        </div>
      </div>
    </div>
  )
}
