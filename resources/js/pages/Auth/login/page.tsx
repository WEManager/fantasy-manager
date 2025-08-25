import { type ChangeEvent, type FormEvent, useEffect, useId } from 'react'
import { Head, Link, useForm } from '@inertiajs/react'

import { Button } from '~/modules/core/components/ui/button'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '~/modules/core/components/ui/card'
import { Checkbox } from '~/modules/core/components/ui/checkbox'
import { Input } from '~/modules/core/components/ui/input'
import { Label } from '~/modules/core/components/ui/label'

interface LoginPageProps {
  status?: string
  canResetPassword?: boolean
}

export default function LoginPage({ status, canResetPassword }: LoginPageProps) {
  const emailId = useId()
  const passwordId = useId()
  const rememberId = useId()

  const { data, setData, post, processing, errors, reset } = useForm({
    email: '',
    password: '',
    remember: false,
  })

  useEffect(() => {
    return () => {
      reset('password')
    }
  }, [reset])

  const onHandleChange = (event: ChangeEvent<HTMLInputElement>) => {
    setData(
      event.target.name as 'email' | 'password' | 'remember',
      event.target.type === 'checkbox' ? event.target.checked : event.target.value,
    )
  }

  const submit = (event: FormEvent) => {
    event.preventDefault()
    post(route('login'))
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-background p-4">
      <Head title="Login" />

      <Card className="w-full max-w-md">
        <CardHeader className="space-y-1">
          <CardTitle className="text-2xl font-bold text-center">Entrar</CardTitle>
          <CardDescription className="text-center">
            Digite suas credenciais para acessar sua conta
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
                autoComplete="username"
                autoFocus
                onChange={onHandleChange}
                required
                className={errors.email ? 'border-destructive focus-visible:ring-destructive' : ''}
              />
              {errors.email && <p className="text-sm text-destructive">{errors.email}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor={passwordId}>Senha</Label>
              <Input
                id={passwordId}
                type="password"
                name="password"
                value={data.password}
                autoComplete="current-password"
                onChange={onHandleChange}
                required
                className={
                  errors.password ? 'border-destructive focus-visible:ring-destructive' : ''
                }
              />
              {errors.password && <p className="text-sm text-destructive">{errors.password}</p>}
            </div>

            <div className="flex items-center space-x-2">
              <Checkbox
                id={rememberId}
                name="remember"
                checked={data.remember}
                onCheckedChange={(checked) => setData('remember', checked as boolean)}
              />
              <Label htmlFor={rememberId} className="text-sm font-normal">
                Lembrar de mim
              </Label>
            </div>

            <div className="flex items-center justify-between pt-4">
              {canResetPassword && (
                <Link
                  href={route('password.request')}
                  className="text-sm text-muted-foreground hover:text-foreground transition-colors"
                >
                  Esqueceu sua senha?
                </Link>
              )}

              <Button type="submit" disabled={processing}>
                {processing ? 'Entrando...' : 'Entrar'}
              </Button>
            </div>

            <div className="text-center pt-4">
              <Link
                href={route('register')}
                className="text-sm text-muted-foreground hover:text-foreground transition-colors"
              >
                Não tem uma conta? Registre-se
              </Link>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  )
}
