import { useQuery } from '@tanstack/react-query'

export function useTournamentQuery() {
  const { data, isLoading, error } = useQuery({
    queryKey: ['tournaments'],
    queryFn: async () => {
      try {
        // Usar URL absoluta para evitar problemas com a função route()
        const url = '/api/tournaments'
        console.log('Fetching tournaments from:', url)

        // Obter o token CSRF do meta tag
        const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')

        const response = await fetch(url, {
          headers: {
            Accept: 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            ...(token && { 'X-CSRF-TOKEN': token }),
          },
        })

        console.log('Response status:', response.status)

        if (!response.ok) {
          const errorText = await response.text()
          console.error('API Error:', errorText)
          throw new Error(`HTTP error! status: ${response.status}`)
        }

        const result = await response.json()
        console.log('Tournaments data:', result)
        return result
      } catch (err) {
        console.error('Fetch error:', err)
        throw err
      }
    },
    retry: 3,
    retryDelay: 1000,
  })

  return {
    tournaments: data?.tournaments || [],
    isLoading,
    error,
  }
}
