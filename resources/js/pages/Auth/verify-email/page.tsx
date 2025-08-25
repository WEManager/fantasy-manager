import type { FormEvent } from 'react'

import { Head, Link, useForm } from '@inertiajs/react'

import { Button } from '~/modules/core/components/ui/button'
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '~/modules/core/components/ui/card'

interface VerifyEmailPageProps {
  status?: string
}

export default function VerifyEmailPage({ status }: VerifyEmailPageProps) {
  const { post, processing } = useForm()

  const submit = (event: FormEvent) => {
    event.preventDefault()
    post(route('verification.send'))
  }

  return (
    <div className="min-h-screen flex items-center justify-center bg-background p-4">
      <Head title="Verificação de email" />

      <Card className="w-full max-w-md">
        <CardHeader className="space-y-1">
          <CardTitle className="text-2xl font-bold text-center">Verificar email</CardTitle>
          <CardDescription className="text-center">
            Obrigado por se registrar! Antes de começar, você poderia verificar seu endereço de
            email clicando no link que acabamos de enviar para você? Se você não recebeu o email,
            ficaremos felizes em enviar outro.
          </CardDescription>
        </CardHeader>

        <CardContent>
          {status === 'verification-link-sent' && (
            <div className="mb-4 p-3 rounded-md bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-400 text-sm">
              Um novo link de verificação foi enviado para o endereço de email fornecido durante o
              registro.
            </div>
          )}

          <form onSubmit={submit} className="space-y-4">
            <div className="flex items-center justify-between pt-4">
              <Button type="submit" disabled={processing}>
                {processing ? 'Enviando...' : 'Reenviar email de verificação'}
              </Button>

              <Link
                href={route('logout')}
                method="post"
                as="button"
                className="text-sm text-muted-foreground hover:text-foreground transition-colors underline"
              >
                Sair
              </Link>
            </div>
          </form>
        </CardContent>
      </Card>
    </div>
  )
}
