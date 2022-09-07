import attributes from '../lang/attributes.json'

export function useLang() {
  const attribute = (key: string): string => {
    if (attributes?.[key].length) return attributes[key]

    return key
  }

  return {
    attribute,
  }
}
