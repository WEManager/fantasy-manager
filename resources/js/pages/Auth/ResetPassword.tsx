import { Head, useForm } from '@inertiajs/react'
import { type ChangeEvent, type FormEvent, useEffect } from 'react'
import { route } from 'ziggy-js'

import Input from '@/Components/Input'
import InputError from '@/components/InputError'
import Label from '@/Components/Label'
import { Button } from '@/components/ui/button'
import Guest from '@/Layouts/Guest'

type ResetPasswordProps = {
  token: string
  email: string
}

export default function ResetPassword({ token, email }: ResetPasswordProps) {
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
    <Guest>
      <Head title="Reset Password" />

      <form onSubmit={submit}>
        <div>
          <Label forInput="email" value="Email" />

          <Input
            type="email"
            name="email"
            value={data.email}
            className="mt-1 block w-full"
            autoComplete="username"
            handleChange={onHandleChange}
          />

          <InputError message={errors.email} className="mt-2" />
        </div>

        <div className="mt-4">
          <Label forInput="password" value="Password" />

          <Input
            type="password"
            name="password"
            value={data.password}
            className="mt-1 block w-full"
            autoComplete="new-password"
            isFocused={true}
            handleChange={onHandleChange}
          />

          <InputError message={errors.password} className="mt-2" />
        </div>

        <div className="mt-4">
          <Label forInput="password_confirmation" value="Confirm Password" />

          <Input
            type="password"
            name="password_confirmation"
            value={data.password_confirmation}
            className="mt-1 block w-full"
            autoComplete="new-password"
            handleChange={onHandleChange}
          />

          <InputError message={errors.password_confirmation} className="mt-2" />
        </div>

        <div className="flex items-center justify-end mt-4">
          <Button className="ml-4" processing={processing}>
            Reset Password
          </Button>
        </div>
      </form>
    </Guest>
  )
}
