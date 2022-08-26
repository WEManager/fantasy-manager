import { ChangeEvent, FormEvent, useEffect } from 'react'
import { Head, Link, useForm } from '@inertiajs/inertia-react'
import route from 'ziggy-js'
import Button from '@/components/Button'
import Guest from '@/Layouts/Guest'
import Input from '@/Components/Input'
import InputError from '@/components/InputError'
import Label from '@/Components/Label'

export default function Register() {
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
  }, [])

  const onHandleChange = (event: ChangeEvent<HTMLInputElement>) => {
    setData(event.target.name as 'name' | 'email' | 'password' | 'password_confirmation', event.target.value)
  }

  const submit = (event: FormEvent) => {
    event.preventDefault()

    post(route('register'))
  }

  return (
    <Guest>
      <Head title="Register" />

      <form onSubmit={submit}>
        <div>
          <Label forInput="name" value="Name" />

          <Input
            type="text"
            name="name"
            value={data.name}
            className="mt-1 block w-full"
            autoComplete="name"
            isFocused={true}
            handleChange={onHandleChange}
            required
          />

          <InputError message={errors.name} className="mt-2" />
        </div>

        <div className="mt-4">
          <Label forInput="email" value="Email" />

          <Input
            type="email"
            name="email"
            value={data.email}
            className="mt-1 block w-full"
            autoComplete="username"
            handleChange={onHandleChange}
            required
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
            handleChange={onHandleChange}
            required
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
            handleChange={onHandleChange}
            required
          />

          <InputError message={errors.password_confirmation} className="mt-2" />
        </div>

        <div className="flex items-center justify-end mt-4">
          <Link href={route('login')} className="underline text-sm text-gray-600 hover:text-gray-900">
            Already registered?
          </Link>

          <Button className="ml-4" processing={processing}>
            Register
          </Button>
        </div>
      </form>
    </Guest>
  )
}
