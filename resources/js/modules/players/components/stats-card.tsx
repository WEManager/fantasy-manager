import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'

interface StatsCardProps {
  title: string
  stats: Record<string, number | null> | null | undefined
}

export function StatsCard({ title, stats }: StatsCardProps) {
  // Verifica se stats existe e é um objeto válido
  if (!stats || typeof stats !== 'object' || Array.isArray(stats)) {
    return (
      <Card>
        <CardHeader>
          <CardTitle className="text-lg">{title}</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="text-center text-gray-500 py-4">Nenhuma estatística disponível</div>
        </CardContent>
      </Card>
    )
  }

  // Filtra apenas as estatísticas que têm valores válidos
  const validStats = Object.entries(stats).filter(
    ([_, value]) => value !== null && value !== undefined && !isNaN(Number(value)),
  )

  if (validStats.length === 0) {
    return (
      <Card>
        <CardHeader>
          <CardTitle className="text-lg">{title}</CardTitle>
        </CardHeader>
        <CardContent>
          <div className="text-center text-gray-500 py-4">
            Nenhuma estatística válida encontrada
          </div>
        </CardContent>
      </Card>
    )
  }

  return (
    <Card>
      <CardHeader>
        <CardTitle className="text-lg">{title}</CardTitle>
      </CardHeader>
      <CardContent>
        <div className="space-y-2">
          {validStats.map(([key, value]) => (
            <div key={key} className="flex justify-between items-center">
              <span className="text-sm font-medium capitalize">{key.replace(/_/g, ' ')}</span>
              <span className="text-sm font-bold text-blue-600">
                {Math.round(Number(value) / 5)}
              </span>
            </div>
          ))}
        </div>
      </CardContent>
    </Card>
  )
}
