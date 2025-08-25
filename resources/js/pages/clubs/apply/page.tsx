import { type ChangeEvent, type FormEvent, useId } from 'react'
import { Head, useForm } from '@inertiajs/react'

import { Button } from '~/modules/core/components/ui/button'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '~/modules/core/components/ui/card'
import { Input } from '~/modules/core/components/ui/input'
import { Label } from '~/modules/core/components/ui/label'

interface Club {
  id: number
  name: string
  locale: string
  manager?: {
    name: string
  }
}

interface ApplyPageProps {
  club: Club
}

export default function ApplyPage({ club }: ApplyPageProps) {
  const fromId = useId()
  const untilId = useId()
  const wageId = useId()

  const { data, setData, post, processing, errors } = useForm({
    club_id: club.id,
    from: new Date().toISOString().split('T')[0],
    until: new Date(Date.now() + 9 * 30 * 24 * 60 * 60 * 1000).toISOString().split('T')[0],
    wage: 100,
  })

  const onHandleChange = (event: ChangeEvent<HTMLInputElement>) => {
    const { name, value, type } = event.target
    setData(name as keyof typeof data, type === 'number' ? parseInt(value) : value)
  }

  const submit = (event: FormEvent) => {
    event.preventDefault()
    post(route('club.apply.store'))
  }

  return (
    <div className="min-h-screen bg-background p-4">
      <Head title={`Aplicar para ${club.name}`} />

      <div className="container mx-auto max-w-4xl">
        <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
          {/* Main Content */}
          <div className="lg:col-span-2">
            <Card>
              <CardHeader>
                <CardTitle className="text-2xl">
                  {club.name} ({club.locale})
                </CardTitle>
                <CardDescription>
                  {club.manager ? <>Gerente: {club.manager.name}</> : <>Sem gerente</>}
                </CardDescription>
              </CardHeader>

              <CardContent>
                <form onSubmit={submit} className="space-y-6">
                  <div className="space-y-2">
                    <Label htmlFor={fromId}>Data de início</Label>
                    <Input
                      id={fromId}
                      type="date"
                      name="from"
                      value={data.from}
                      onChange={onHandleChange}
                      required
                      className={
                        errors.from ? 'border-destructive focus-visible:ring-destructive' : ''
                      }
                    />
                    {errors.from && <p className="text-sm text-destructive">{errors.from}</p>}
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor={untilId}>Data de término</Label>
                    <Input
                      id={untilId}
                      type="date"
                      name="until"
                      value={data.until}
                      onChange={onHandleChange}
                      required
                      className={
                        errors.until ? 'border-destructive focus-visible:ring-destructive' : ''
                      }
                    />
                    {errors.until && <p className="text-sm text-destructive">{errors.until}</p>}
                  </div>

                  <div className="space-y-2">
                    <Label htmlFor={wageId}>Salário</Label>
                    <div className="relative">
                      <Input
                        id={wageId}
                        type="number"
                        name="wage"
                        value={data.wage}
                        onChange={onHandleChange}
                        required
                        className={
                          errors.wage
                            ? 'border-destructive focus-visible:ring-destructive pr-20'
                            : 'pr-20'
                        }
                      />
                      <div className="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <span className="text-muted-foreground text-sm">por mês</span>
                      </div>
                    </div>
                    {errors.wage && <p className="text-sm text-destructive">{errors.wage}</p>}
                  </div>

                  <div className="flex justify-end pt-4">
                    <Button type="submit" disabled={processing}>
                      {processing ? 'Enviando...' : 'Enviar aplicação'}
                    </Button>
                  </div>
                </form>
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
