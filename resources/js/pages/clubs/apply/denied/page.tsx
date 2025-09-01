import { Head, Link } from '@inertiajs/react'

import { Button } from '~/modules/core/components/ui/button'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '~/modules/core/components/ui/card'

interface Club {
  id: number
  name: string
}

interface JobApplication {
  id: number
  created_at: string
  status: string
}

interface DeniedPageProps {
  club: Club
  job_application: JobApplication
}

export default function DeniedPage({ club, job_application }: DeniedPageProps) {
  const applicationDate = new Date(job_application.created_at).toLocaleDateString('pt-BR')

  return (
    <div className="min-h-screen bg-background p-4">
      <Head title={`Aplicação negada - ${club.name}`} />

      <div className="container mx-auto max-w-4xl">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Main Content */}
          <div className="lg:col-span-2">
            <Card>
              <CardHeader>
                <CardTitle className="text-2xl">{club.name}</CardTitle>
                <CardDescription>Aplicação para gerente</CardDescription>
              </CardHeader>

              <CardContent>
                <div className="space-y-4">
                  <div className="p-4 rounded-md bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800">
                    <p className="text-red-700 dark:text-red-400">
                      {club.name} rejeitou sua aplicação de emprego de {applicationDate}.
                    </p>
                  </div>

                  <div className="flex gap-4">
                    <Button asChild variant="outline">
                      <Link href={route('home')}>Voltar ao início</Link>
                    </Button>

                    <Button asChild>
                      <Link href={`/clubs/${club.id}`}>Ver outros clubes</Link>
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
