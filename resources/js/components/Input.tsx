import { type ChangeEvent, useEffect, useRef } from 'react'

type InputProps = {
  type: string
  name: string
  value: any
  className?: string
  autoComplete?: string
  required?: boolean
  isFocused?: boolean
  handleChange: (event: ChangeEvent<HTMLInputElement>) => void
}

export default function Input({
  type = 'text',
  name,
  value,
  className,
  autoComplete,
  required = false,
  isFocused = false,
  handleChange,
}: InputProps) {
  const input = useRef<HTMLInputElement>(null)

  useEffect(() => {
    if (isFocused) {
      input.current?.focus()
    }
  }, [isFocused])

  return (
    <div className="flex flex-col items-start">
      <input
        type={type}
        name={name}
        value={value}
        className={`border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm ${className}`}
        ref={input}
        autoComplete={autoComplete}
        required={required}
        onChange={(e) => handleChange(e)}
      />
    </div>
  )
}
