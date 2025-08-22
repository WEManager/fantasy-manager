import type { PropsWithChildren } from 'react'

type LabelProps = PropsWithChildren & {
  forInput: string
  className?: string
  value?: string
}

export default function Label({ forInput, value, className, children }: LabelProps) {
  return (
    <label htmlFor={forInput} className={`block font-medium text-sm text-gray-700 ${className}`}>
      {value ? value : children}
    </label>
  )
}
