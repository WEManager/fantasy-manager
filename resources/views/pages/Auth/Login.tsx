import { ChangeEvent, FormEvent, useEffect } from 'react'
import { Head, Link, useForm } from '@inertiajs/inertia-react'
import route from 'ziggy-js'
import Button from '@/components/Button'
import Checkbox from '@/components/Checkbox'
import Guest from '@/Layouts/Guest'
import Input from '@/components/Input'
import InputError from '@/components/InputError'
import Label from '@/components/Label'

export default function Login({ status, canResetPassword }: any) {
  const { data, setData, post, processing, errors, reset } = useForm({
    email: '',
    password: '',
    remember: '',
  })

  useEffect(() => {
    return () => {
      reset('password')
    }
  }, [])

  const onHandleChange = (event: ChangeEvent<HTMLInputElement>) => {
    setData(
      event.target.name as 'email' | 'password' | 'remember',
      event.target.type === 'checkbox' ? String(event.target.checked) : event.target.value
    )
  }

  const submit = (event: FormEvent) => {
    event.preventDefault()

    post(route('login'))
  }

  return (
    <Guest>
      <Head title="Log in" />

      {status && <div className="mb-4 font-medium text-sm text-green-600">{status}</div>}

      <form onSubmit={submit}>
        <div>
          <Label forInput="email" value="Email" />

          <Input
            type="text"
            name="email"
            value={data.email}
            className="mt-1 block w-full"
            autoComplete="username"
            handleChange={onHandleChange}
            isFocused
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
            autoComplete="current-password"
            handleChange={onHandleChange}
          />

          <InputError message={errors.password} className="mt-2" />
        </div>

        <div className="block mt-4">
          <Label forInput="remember" className="flex items-center">
            <Checkbox name="remember" value={data.remember} handleChange={onHandleChange} />

            <span className="ml-2 text-sm text-gray-600">Remember me</span>
          </Label>
        </div>

        <div className="flex items-center justify-end mt-4">
          {canResetPassword && (
            <Link href={route('password.request')} className="underline text-sm text-gray-600 hover:text-gray-900">
              Forgot your password?
            </Link>
          )}

          <Button className="ml-4" processing={processing}>
            Log in
          </Button>
        </div>
      </form>
    </Guest>
  )
}
