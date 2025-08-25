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
  locale: string
}

interface LowLevelPageProps {
  club: Club
}

export default function LowLevelPage({ club }: LowLevelPageProps) {
  return (
    <div className="min-h-screen bg-background p-4">
      <Head title={`Aplicação - ${club.name}`} />

      <div className="container mx-auto max-w-4xl">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Main Content */}
          <div className="lg:col-span-2">
            <Card>
              <CardHeader>
                <CardTitle className="text-2xl">
                  {club.name} ({club.locale})
                </CardTitle>
                <CardDescription>Aplicação para gerente</CardDescription>
              </CardHeader>

              <CardContent>
                <div className="space-y-6">
                  <div className="p-4 rounded-md bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800">
                    <p className="text-blue-700 dark:text-blue-400">
                      Você precisa passar no exame para a licença básica de gerente para poder se
                      candidatar a um emprego como gerente em um clube. Por favor, esteja ciente de
                      que as perguntas estão principalmente lá para não permitir que bots assumam o
                      controle das equipes. É por isso que elas podem parecer um pouco estranhas.
                    </p>
                  </div>

                  <div className="flex gap-4">
                    <Button asChild>
                      <Link href={route('license_test', { licenseQuiz: 1 })}>Fazer Teste</Link>
                    </Button>

                    <Button asChild variant="outline">
                      <Link href={route('home')}>Voltar ao início</Link>
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
