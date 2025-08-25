import type { Club } from '~/modules/core/types'

import { Card, CardContent } from '~/modules/core/components/ui/card'

interface ClubHeaderProps {
  club: Club
}

export function ClubHeader({ club }: ClubHeaderProps) {
  return (
    <div className="bg-card border-b">
      <div className="container mx-auto px-4 py-6">
        <Card className="border-0 shadow-none bg-transparent">
          <CardContent className="p-0">
            <div className="flex items-center space-x-4">
              <div
                className="w-16 h-16 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-lg"
                style={{
                  background: `linear-gradient(45deg, ${club.colors?.[0] || '#3B82F6'} 0%, ${club.colors?.[1] || '#1D4ED8'} 100%)`,
                }}
              >
                {club.name.charAt(0)}
              </div>

              <div>
                <h1 className="text-3xl font-bold text-card-foreground">{club.name}</h1>
                <p className="text-muted-foreground">Clube de Futebol</p>
              </div>
            </div>
          </CardContent>
        </Card>
      </div>
    </div>
  )
}
