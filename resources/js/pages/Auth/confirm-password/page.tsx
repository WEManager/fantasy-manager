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

export default function ConfirmPasswordPage() {
  const passwordId = useId()

  const { data, setData, post, processing, errors, reset } = useForm({
    password: '',
  })

  useEffect(() => {
    return () => {
      reset('password')
    }
  }, [reset])

  const onHandleChange = (event: ChangeEvent<HTMLInputElement>) => {
    setData(event.target.name as 'password', event.target.value)
  }

  const submit = (event: FormEvent) => {
    event.preventDefault()
    post(route('password.confirm'))
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-background p-4">
      <Head title="Confirmar senha" />

      <Card className="w-full max-w-md">
        <CardHeader className="space-y-1">
          <CardTitle className="text-2xl font-bold text-center">Confirmar senha</CardTitle>
          <CardDescription className="text-center">
            Esta é uma área segura da aplicação. Confirme sua senha antes de continuar.
          </CardDescription>
        </CardHeader>

        <CardContent>
          <form onSubmit={submit} className="space-y-4">
            <div className="space-y-2">
              <Label htmlFor={passwordId}>Senha</Label>
              <Input
                id={passwordId}
                type="password"
                name="password"
                value={data.password}
                autoComplete="current-password"
                autoFocus
                onChange={onHandleChange}
                required
                className={
                  errors.password ? 'border-destructive focus-visible:ring-destructive' : ''
                }
              />
              {errors.password && <p className="text-sm text-destructive">{errors.password}</p>}
            </div>

            <div className="flex items-center justify-end pt-4">
              <Button type="submit" disabled={processing}>
                {processing ? 'Confirmando...' : 'Confirmar'}
              </Button>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  )
}
