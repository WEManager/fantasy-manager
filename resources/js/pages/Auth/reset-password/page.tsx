import { type ChangeEvent, type FormEvent, useEffect, useId } from 'react'
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

interface ResetPasswordPageProps {
  token: string
  email: string
}

export default function ResetPasswordPage({ token, email }: ResetPasswordPageProps) {
  const emailId = useId()
  const passwordId = useId()
  const passwordConfirmationId = useId()

  const { data, setData, post, processing, errors, reset } = useForm({
    token: token,
    email: email,
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
      event.target.name as 'token' | 'email' | 'password' | 'password_confirmation',
      event.target.value,
    )
  }

  const submit = (event: FormEvent) => {
    event.preventDefault()
    post(route('password.update'))
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-background p-4">
      <Head title="Redefinir senha" />

      <Card className="w-full max-w-md">
        <CardHeader className="space-y-1">
          <CardTitle className="text-2xl font-bold text-center">Redefinir senha</CardTitle>
          <CardDescription className="text-center">
            Digite sua nova senha para redefinir sua conta
          </CardDescription>
        </CardHeader>

        <CardContent>
          <form onSubmit={submit} className="space-y-4">
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
              <Label htmlFor={passwordId}>Nova senha</Label>
              <Input
                id={passwordId}
                type="password"
                name="password"
                value={data.password}
                autoComplete="new-password"
                autoFocus
                onChange={onHandleChange}
                required
                className={
                  errors.password ? 'border-destructive focus-visible:ring-destructive' : ''
                }
              />
              {errors.password && <p className="text-sm text-destructive">{errors.password}</p>}
            </div>

            <div className="space-y-2">
              <Label htmlFor={passwordConfirmationId}>Confirmar nova senha</Label>
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

            <div className="flex items-center justify-end pt-4">
              <Button type="submit" disabled={processing}>
                {processing ? 'Redefinindo...' : 'Redefinir senha'}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  )
}
