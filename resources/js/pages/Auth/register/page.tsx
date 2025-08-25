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
import { Input } from '~/modules/core/components/ui/input'
import { Label } from '~/modules/core/components/ui/label'

export default function RegisterPage() {
  const nameId = useId()
  const emailId = useId()
  const passwordId = useId()
  const passwordConfirmationId = useId()

  const { data, setData, post, processing, errors, reset } = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
  })

  useEffect(() => {
    return () => {
      reset('password', 'password_confirmation')
    }
  }, [reset])

  const onHandleChange = (event: ChangeEvent<HTMLInputElement>) => {
    setData(
      event.target.name as 'name' | 'email' | 'password' | 'password_confirmation',
      event.target.value,
    )
  }

  const submit = (event: FormEvent) => {
    event.preventDefault()
    post(route('register'))
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-background p-4">
      <Head title="Registro" />

      <Card className="w-full max-w-md">
        <CardHeader className="space-y-1">
          <CardTitle className="text-2xl font-bold text-center">Criar Conta</CardTitle>
          <CardDescription className="text-center">
            Digite suas informações para criar sua conta
          </CardDescription>
        </CardHeader>

        <CardContent>
          <form onSubmit={submit} className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor={nameId}>Nome</Label>
              <Input
                id={nameId}
                type="text"
                name="name"
                value={data.name}
                autoComplete="name"
                autoFocus
                onChange={onHandleChange}
                required
                className={errors.name ? 'border-destructive focus-visible:ring-destructive' : ''}
              />
              {errors.name && <p className="text-sm text-destructive">{errors.name}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor={emailId}>Email</Label>
              <Input
                id={emailId}
                type="email"
                name="email"
                value={data.email}
                autoComplete="username"
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
                autoComplete="new-password"
                onChange={onHandleChange}
                required
                className={
                  errors.password ? 'border-destructive focus-visible:ring-destructive' : ''
                }
              />
              {errors.password && <p className="text-sm text-destructive">{errors.password}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor={passwordConfirmationId}>Confirmar Senha</Label>
              <Input
                id={passwordConfirmationId}
                type="password"
                name="password_confirmation"
                value={data.password_confirmation}
                autoComplete="new-password"
                onChange={onHandleChange}
                required
                className={
                  errors.password_confirmation
                    ? 'border-destructive focus-visible:ring-destructive'
                    : ''
                }
              />
              {errors.password_confirmation && (
                <p className="text-sm text-destructive">{errors.password_confirmation}</p>
              )}
            </div>

            <div className="flex items-center justify-between pt-4">
              <Link
                href={route('login')}
                className="text-sm text-muted-foreground hover:text-foreground transition-colors"
              >
                Já tem uma conta?
              </Link>

              <Button type="submit" disabled={processing}>
                {processing ? 'Criando conta...' : 'Criar Conta'}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  )
}
