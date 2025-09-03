export function age(birthdate: string | Date): number {
  const birthdateValue = typeof birthdate === 'string' ? new Date(birthdate) : birthdate

  return Math.floor((Date.now() - birthdateValue.getTime()) / 3.15576e10)
}
