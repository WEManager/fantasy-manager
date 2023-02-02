type CheckboxProps = {
  name: string
  value: any
  handleChange: (event: any) => void
}

export default function Checkbox({ name, value, handleChange }: CheckboxProps) {
  return (
    <input
      type="checkbox"
      name={name}
      value={value}
      className="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
      onChange={(e) => handleChange(e)}
    />
  )
}