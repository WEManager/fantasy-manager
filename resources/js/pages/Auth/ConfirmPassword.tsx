import { Head, useForm } from '@inertiajs/react'
import { type ChangeEvent, type FormEvent, useEffect } from 'react'
import { route } from 'ziggy-js'

import Input from '~/Components/Input'
import InputError from '~/components/InputError'
import Label from '~/Components/Label'
import { Button } from '~/components/ui/button'
import Guest from '~/Layouts/Guest'

export default function ConfirmPassword() {
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
    <Guest>
      <Head title="Confirm Password" />

      <div className="mb-4 text-sm text-gray-600">
        This is a secure area of the application. Please confirm your password before continuing.
      </div>

      <form onSubmit={submit}>
        <div className="mt-4">
          <Label forInput="password" value="Password" />

          <Input
            type="password"
            name="password"
            value={data.password}
            className="mt-1 block w-full"
            isFocused
            handleChange={onHandleChange}
          />

          <InputError message={errors.password} className="mt-2" />
        </div>

        <div className="flex items-center justify-end mt-4">
          <Button className="ml-4" processing={processing}>
            Confirm
          </Button>
        </div>
      </form>
    </Guest>
  )
}
