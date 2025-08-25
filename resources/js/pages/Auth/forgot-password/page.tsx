import { type ChangeEvent, type FormEvent, useId } from 'react'
import { Head, Link, useForm } from '@inertiajs/react'

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

interface ForgotPasswordPageProps {
  status?: string
}

export default function ForgotPasswordPage({ status }: ForgotPasswordPageProps) {
  const emailId = useId()

  const { data, setData, post, processing, errors } = useForm({
    email: '',
  })

  const onHandleChange = (event: ChangeEvent<HTMLInputElement>) => {
    setData(event.target.name as 'email', event.target.value)
  }

  const submit = (event: FormEvent) => {
    event.preventDefault()
    post(route('password.email'))
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-background p-4">
      <Head title="Esqueci minha senha" />

      <Card className="w-full max-w-md">
        <CardHeader className="space-y-1">
          <CardTitle className="text-2xl font-bold text-center">Esqueci minha senha</CardTitle>
          <CardDescription className="text-center">
            Digite seu email e enviaremos um link para redefinir sua senha
          </CardDescription>
        </CardHeader>

        <CardContent>
          {status && (
            <div className="mb-4 p-3 rounded-md bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm">
              {status}
            </div>
          )}

          <form onSubmit={submit} className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor={emailId}>Email</Label>
              <Input
                id={emailId}
                type="email"
                name="email"
                value={data.email}
                autoComplete="email"
                autoFocus
                onChange={onHandleChange}
                required
                className={errors.email ? 'border-destructive focus-visible:ring-destructive' : ''}
              />
              {errors.email && <p className="text-sm text-destructive">{errors.email}</p>}
            </div>

            <div className="flex items-center justify-between pt-4">
              <Link
                href={route('login')}
                className="text-sm text-muted-foreground hover:text-foreground transition-colors"
              >
                Voltar para o login
              </Link>

              <Button type="submit" disabled={processing}>
                {processing ? 'Enviando...' : 'Enviar link de redefinição'}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  )
}
