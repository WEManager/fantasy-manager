import { Card, CardContent, CardHeader, CardTitle } from '~/modules/core/components/ui/card'

interface StatsCardProps {
  title: string
  stats: Record<string, number>
}

export function StatsCard({ title, stats }: StatsCardProps) {
  return (
    <Card>
      <CardHeader>
        <CardTitle className="text-lg">{title}</CardTitle>
      </CardHeader>
      <CardContent>
        <div className="space-y-2">
          {Object.entries(stats).map(([key, value]) => (
            <div key={key} className="flex justify-between items-center">
              <span className="text-sm font-medium capitalize">{key.replace(/_/g, ' ')}</span>
              <span className="text-sm font-bold text-blue-600">{Math.round(value / 5)}</span>
            </div>
          ))}
        </div>
      </CardContent>
    </Card>
  )
}
